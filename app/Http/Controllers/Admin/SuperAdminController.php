<?php

namespace App\Http\Controllers\Admin;

use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SuperAdminController extends AdminController
{
    private function ensureSuperAdmin(): void
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }
    }

    public function index(): View
    {
        $this->ensureSuperAdmin();

        $restaurants = Restaurant::with(['users' => fn($q) => $q->where('role', 'owner')])
            ->withTrashed()
            ->latest()
            ->get();

        $stats = [
            'total'   => Restaurant::withTrashed()->count(),
            'trial'   => Restaurant::where(function ($q) {
                $q->whereNotNull('trial_ends_at')->where('trial_ends_at', '>', now());
            })->count(),
            'paid'    => Restaurant::whereIn('plan', ['pedidos', 'full'])->count(),
            'expired' => Restaurant::where(function ($q) {
                $q->whereNull('subscription_ends_at')->whereNull('trial_ends_at');
            })->orWhere(function ($q) {
                $q->whereNotNull('subscription_ends_at')->where('subscription_ends_at', '<', now());
            })->count(),
        ];

        $expiringSoon = Restaurant::whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '>', now())
            ->where('subscription_ends_at', '<=', now()->addDays(7))
            ->with(['users' => fn ($q) => $q->where('role', 'owner')])
            ->orderBy('subscription_ends_at')
            ->get();

        $expired = Restaurant::where(function ($q) {
            $q->whereNotNull('subscription_ends_at')
              ->where('subscription_ends_at', '<', now());
        })->orWhere(function ($q) {
            $q->whereNull('subscription_ends_at')
              ->whereNotNull('trial_ends_at')
              ->where('trial_ends_at', '<', now()->subDays(7));
        })
        ->with(['users' => fn ($q) => $q->where('role', 'owner')])
        ->orderBy('subscription_ends_at')
        ->get();

        return view('superadmin.index', compact('restaurants', 'stats', 'expiringSoon', 'expired'));
    }

    public function createRestaurant(): \Illuminate\View\View
    {
        $this->ensureSuperAdmin();
        return view('superadmin.create');
    }

    public function storeRestaurant(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'restaurant_name'  => ['required', 'string', 'max:255'],
            'restaurant_slug'  => ['required', 'string', 'max:255', 'alpha_dash', 'unique:restaurants,slug'],
            'owner_name'       => ['required', 'string', 'max:255'],
            'owner_email'      => ['required', 'email', 'unique:users,email'],
            'owner_password'   => ['required', 'string', 'min:8'],
            'plan'             => ['required', 'in:carta,pedidos,full'],
            'tables'           => ['nullable', 'integer', 'min:0', 'max:50'],
        ]);

        $slug = \Illuminate\Support\Str::slug($validated['restaurant_slug']);

        $restaurant = \App\Models\Restaurant::create([
            'name'          => $validated['restaurant_name'],
            'slug'          => $slug,
            'plan'          => $validated['plan'],
            'trial_ends_at' => now()->addDays(14),
            'is_active'     => true,
        ]);

        $owner = \App\Models\User::create([
            'name'          => $validated['owner_name'],
            'email'         => $validated['owner_email'],
            'password'      => \Illuminate\Support\Facades\Hash::make($validated['owner_password']),
            'restaurant_id' => $restaurant->id,
            'role'          => 'owner',
        ]);

        $tags = [];
        $tables = (int) ($validated['tables'] ?? 0);
        for ($i = 1; $i <= $tables; $i++) {
            $tags[] = \App\Models\NfcTag::create([
                'code'          => \App\Models\NfcTag::generateCode(),
                'type'          => 'menu',
                'restaurant_id' => $restaurant->id,
                'label'         => 'Mesa ' . $i,
                'is_active'     => true,
            ]);
        }

        // Siempre crear un tag de menú principal
        $menuTag = \App\Models\NfcTag::create([
            'code'          => \App\Models\NfcTag::generateCode(),
            'type'          => 'menu',
            'restaurant_id' => $restaurant->id,
            'label'         => 'QR Menú',
            'is_active'     => true,
        ]);

        return redirect()->route('superadmin.credentials', [
            'restaurant' => $restaurant->id,
        ])->with([
            'credentials_restaurant' => $restaurant,
            'credentials_owner'      => $owner,
            'credentials_password'   => $validated['owner_password'],
            'credentials_tags'       => array_merge($tags, [$menuTag]),
        ]);
    }

    public function credentials(\Illuminate\Http\Request $request, \App\Models\Restaurant $restaurant): \Illuminate\View\View
    {
        $this->ensureSuperAdmin();

        // Data comes from session flash
        $data = [
            'restaurant' => session('credentials_restaurant', $restaurant),
            'owner'      => session('credentials_owner'),
            'password'   => session('credentials_password'),
            'tags'       => session('credentials_tags', []),
        ];

        return view('superadmin.credentials', compact('data'));
    }

    public function updatePlan(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'plan'                 => ['required', 'in:carta,pedidos,full'],
            'trial_ends_at'        => ['nullable', 'date'],
            'subscription_ends_at' => ['nullable', 'date'],
            'is_active'            => ['nullable', 'boolean'],
        ], [
            'plan.required' => 'El plan es requerido.',
            'plan.in'       => 'Plan no válido. Opciones: carta, pedidos, full.',
        ]);

        $restaurant->update($validated);

        return redirect()->back()->with('success', 'Restaurante actualizado.');
    }
}
