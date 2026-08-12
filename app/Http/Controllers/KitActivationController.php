<?php

namespace App\Http\Controllers;

use App\Models\Kit;
use App\Models\NfcTag;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class KitActivationController extends Controller
{
    // GET /activar/{token}
    public function show(string $token): Response
    {
        $kit = Kit::with('nfcTags')->where('token', $token)->first();

        if (! $kit) {
            return response()->view('kit.show', ['state' => 'not_found'], 200);
        }

        if ($kit->status === 'activado') {
            return response()->view('kit.show', [
                'state' => 'already_activated',
                'kit'   => $kit,
            ], 200);
        }

        $tableCount = $kit->nfcTags->where('type', 'menu')->pluck('slot_label')->unique()->count();
        $hasReview  = $kit->nfcTags->where('type', 'review')->isNotEmpty();

        $user        = auth()->user();
        $restaurants = collect();

        if ($user) {
            $restaurants = $user->restaurants()->orderBy('name')->get();
            if ($restaurants->isEmpty() && $user->restaurant_id) {
                $restaurants = collect([$user->restaurant]);
            }
        }

        return view('kit.show', [
            'state'       => 'available',
            'kit'         => $kit,
            'token'       => $token,
            'tableCount'  => $tableCount,
            'hasReview'   => $hasReview,
            'user'        => $user,
            'restaurants' => $restaurants,
        ]);
    }

    // POST /activar/{token}/register  (solo guest)
    public function processRegister(Request $request, string $token): RedirectResponse
    {
        $kit = Kit::where('token', $token)->first();

        if (! $kit || $kit->status === 'activado') {
            return redirect()->route('kit.show', $token)
                ->with('error', 'Este kit ya no está disponible para activar.');
        }

        $data = $request->validate([
            'restaurant_name' => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password'        => ['required', 'min:8', 'confirmed'],
        ]);

        $slug = $this->generateSlug($data['restaurant_name']);

        $restaurant = null;
        $user       = null;

        try {
            DB::transaction(function () use ($data, $slug, $kit, &$restaurant, &$user) {
                $restaurant = Restaurant::create([
                    'name'      => $data['restaurant_name'],
                    'slug'      => $slug,
                    'plan'      => 'free',
                    'is_active' => true,
                ]);

                $user = User::create([
                    'name'          => $data['restaurant_name'],
                    'email'         => $data['email'],
                    'password'      => Hash::make($data['password']),
                    'restaurant_id' => $restaurant->id,
                    'role'          => 'owner',
                ]);

                $this->runActivation($kit, $restaurant->id);
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'already_activated') {
                return redirect()->route('kit.show', $token)
                    ->with('error', 'Este kit ya fue activado por otra persona en este momento.');
            }
            throw $e;
        }

        Auth::login($user);
        $request->session()->regenerate();
        session(['active_restaurant_id' => $restaurant->id]);

        return redirect()->route('admin.dashboard')->with('kit_activated', true);
    }

    // POST /activar/{token}/login  (solo guest)
    public function processLogin(Request $request, string $token): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, false)) {
            return back()->withErrors(['email' => 'Credenciales incorrectas.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Vuelve a la página del kit, ahora autenticado
        return redirect()->route('kit.show', $token);
    }

    // POST /activar/{token}/activate  (solo auth)
    public function activate(Request $request, string $token): RedirectResponse
    {
        $kit = Kit::where('token', $token)->firstOrFail();

        $user = auth()->user();

        $restaurants = $user->restaurants()->orderBy('name')->get();
        if ($restaurants->isEmpty() && $user->restaurant_id) {
            $restaurants = collect([$user->restaurant]);
        }

        if ($restaurants->isEmpty()) {
            return redirect()->route('kit.show', $token)
                ->with('error', 'Tu cuenta no tiene ningún negocio asociado.');
        }

        if ($restaurants->count() === 1) {
            $restaurant = $restaurants->first();
        } else {
            $request->validate(['restaurant_id' => ['required', 'integer']]);
            $restaurant = $restaurants->firstWhere('id', (int) $request->restaurant_id);
            if (! $restaurant) {
                abort(403);
            }
        }

        try {
            DB::transaction(function () use ($kit, $restaurant) {
                $this->runActivation($kit, $restaurant->id);
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'already_activated') {
                return redirect()->route('kit.show', $token)
                    ->with('error', 'Este kit ya fue activado. Si es tuyo, entra a tu cuenta.');
            }
            throw $e;
        }

        session(['active_restaurant_id' => $restaurant->id]);

        return redirect()->route('admin.dashboard')->with('kit_activated', true);
    }

    // -----------------------------------------------------------------
    // Lógica de activación (siempre dentro de una transacción abierta)
    // -----------------------------------------------------------------
    private function runActivation(Kit $kit, int $restaurantId): void
    {
        // Bloqueo a nivel de fila — previene dos activaciones simultáneas
        $fresh = Kit::lockForUpdate()->findOrFail($kit->id);

        if ($fresh->status === 'activado') {
            throw new \RuntimeException('already_activated');
        }

        $tags     = $fresh->nfcTags()->orderBy('id')->get()->groupBy('slot_label');
        $maxOrder = RestaurantTable::where('restaurant_id', $restaurantId)->max('order') ?? 0;

        foreach ($tags as $slotLabel => $slotTags) {
            if ($slotLabel === 'Reseñas') {
                // Tag de letrero: asignar negocio pero sin crear mesa
                foreach ($slotTags as $tag) {
                    $tag->update(['restaurant_id' => $restaurantId, 'is_active' => true]);
                }
                continue;
            }

            // Par de mesa (QR + NFC), ordenados por id para consistencia
            $sorted = $slotTags->sortBy('id')->values();
            $qrTag  = $sorted->get(0);
            $nfcTag = $sorted->get(1);

            $qrTag->update(['restaurant_id' => $restaurantId, 'label' => $slotLabel, 'is_active' => true]);

            if ($nfcTag) {
                $nfcTag->update(['restaurant_id' => $restaurantId, 'label' => $slotLabel, 'is_active' => true]);
            }

            RestaurantTable::create([
                'restaurant_id'    => $restaurantId,
                'name'             => $slotLabel,
                'qr_tag_id'        => $qrTag->id,
                'nfc_tag_id'       => $nfcTag?->id,
                'is_active'        => true,
                'order'            => ++$maxOrder,
                'nfc_chip_written' => true, // los chips vienen pregrabados de fábrica
            ]);
        }

        $fresh->update([
            'status'        => 'activado',
            'activated_at'  => now(),
            'restaurant_id' => $restaurantId,
        ]);

        // Plan Básico por 2 meses a partir de la activación
        Restaurant::where('id', $restaurantId)->update([
            'plan'                 => 'basico',
            'subscription_ends_at' => now()->addMonths(2),
        ]);
    }

    // -----------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------
    private function generateSlug(string $name): string
    {
        $reserved = [
            'admin', 'login', 'register', 'logout', 'superadmin', 'dashboard',
            'profile', 'board', 'sitemap', 'r', 'm', 'activar', 'wa-click',
        ];

        $base = Str::slug($name) ?: 'negocio';
        $slug = $base;
        $i    = 1;

        while (in_array($slug, $reserved) || Restaurant::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
