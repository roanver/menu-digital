<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function index(Request $request): View
    {
        $this->ensureSuperAdmin();

        // Filters
        $search  = $request->input('q', '');
        $status  = $request->input('status', '');
        $plan    = $request->input('plan', '');
        $type    = $request->input('type', '');
        $sort    = $request->input('sort', 'recent');

        $query = Restaurant::with([
            'users'    => fn ($q) => $q->where('role', 'owner'),
            'members'  => fn ($q) => $q->where('restaurant_user.role', 'owner'),
            'payments' => fn ($q) => $q->active()->latest('paid_at')->limit(1),
        ])
        ->withTrashed()
        ->withCount('nfcTags');

        // Monthly scans subquery
        $monthStart = now()->startOfMonth()->toDateString();
        $query->addSelect([
            'monthly_scans' => DB::table('nfc_scans')
                ->join('nfc_tags', 'nfc_scans.nfc_tag_id', '=', 'nfc_tags.id')
                ->whereColumn('nfc_tags.restaurant_id', 'restaurants.id')
                ->where('nfc_scans.date', '>=', $monthStart)
                ->selectRaw('COALESCE(SUM(nfc_scans.count), 0)'),
        ]);

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('restaurants.name', 'like', "%{$search}%")
                  ->orWhere('restaurants.slug', 'like', "%{$search}%")
                  ->orWhere('restaurants.phone', 'like', "%{$search}%")
                  ->orWhere('restaurants.whatsapp', 'like', "%{$search}%")
                  ->orWhereHas('users', fn ($u) => $u->where('email', 'like', "%{$search}%"));
            });
        }

        // Status filter
        match ($status) {
            'active'  => $query->whereNotDeleted()->where(function ($q) {
                $q->where('plan', 'free')
                  ->orWhere(fn ($q) => $q->whereNotNull('trial_ends_at')->where('trial_ends_at', '>', now()))
                  ->orWhere(fn ($q) => $q->whereNotNull('subscription_ends_at')->where('subscription_ends_at', '>', now()));
            }),
            'trial'   => $query->whereNotDeleted()->whereNotNull('trial_ends_at')->where('trial_ends_at', '>', now()),
            'expired' => $query->whereNotDeleted()->where('plan', '!=', 'free')->where(function ($q) {
                $q->where(fn ($q) => $q->whereNull('subscription_ends_at')->where(fn ($q) => $q->whereNull('trial_ends_at')->orWhere('trial_ends_at', '<', now())))
                  ->orWhere(fn ($q) => $q->whereNotNull('subscription_ends_at')->where('subscription_ends_at', '<', now()));
            }),
            'free'    => $query->whereNotDeleted()->where('plan', 'free'),
            default   => null,
        };

        // Plan filter
        if ($plan) {
            $query->where('plan', $plan);
        }

        // Type filter
        if ($type) {
            $query->where('type', $type);
        }

        // Sort
        match ($sort) {
            'expiry'  => $query->orderByRaw('COALESCE(subscription_ends_at, trial_ends_at) ASC NULLS LAST'),
            'scans'   => $query->orderBy('monthly_scans', 'desc'),
            'login'   => $query->orderByRaw('(SELECT MAX(last_login_at) FROM users WHERE users.restaurant_id = restaurants.id) DESC NULLS LAST'),
            default   => $query->latest('restaurants.created_at'),
        };

        $restaurants = $query->paginate(30)->withQueryString();

        // Stats
        $planPrices = ['basico' => 9990, 'pro' => 19990];
        $activeNonFree = Restaurant::whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '>', now())
            ->whereIn('plan', ['basico', 'pro'])
            ->get(['plan']);

        $mrr = $activeNonFree->sum(fn ($r) => $planPrices[$r->plan] ?? 0);

        $billedThisMonth = Payment::active()
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $pendingCount = Restaurant::where('plan', '!=', 'free')
            ->where(function ($q) {
                $q->where(function ($q) {
                    $q->whereNotNull('subscription_ends_at')
                      ->where('subscription_ends_at', '<', now());
                })->orWhere(function ($q) {
                    $q->whereNotNull('subscription_ends_at')
                      ->whereBetween('subscription_ends_at', [now(), now()->endOfMonth()]);
                });
            })->count();

        $stats = [
            'total'   => Restaurant::withTrashed()->count(),
            'mrr'     => $mrr,
            'billed'  => $billedThisMonth,
            'pending' => $pendingCount,
        ];

        // Cobranza lists (all, no pagination)
        $cobranzaBase = Restaurant::with([
            'users'   => fn ($q) => $q->where('role', 'owner'),
            'members' => fn ($q) => $q->where('restaurant_user.role', 'owner'),
        ])->where('plan', '!=', 'free');

        $expired = (clone $cobranzaBase)
            ->where(function ($q) {
                $q->whereNotNull('subscription_ends_at')->where('subscription_ends_at', '<', now());
            })
            ->orderBy('subscription_ends_at')
            ->get();

        $expiring7 = (clone $cobranzaBase)
            ->whereNotNull('subscription_ends_at')
            ->whereBetween('subscription_ends_at', [now(), now()->addDays(7)])
            ->orderBy('subscription_ends_at')
            ->get();

        $expiring30 = (clone $cobranzaBase)
            ->whereNotNull('subscription_ends_at')
            ->whereBetween('subscription_ends_at', [now()->addDays(7), now()->addDays(30)])
            ->orderBy('subscription_ends_at')
            ->get();

        return view('superadmin.index', compact(
            'restaurants', 'stats',
            'expired', 'expiring7', 'expiring30',
            'search', 'status', 'plan', 'type', 'sort'
        ));
    }

    public function show(Restaurant $restaurant): View
    {
        $this->ensureSuperAdmin();

        $restaurant->load([
            'users'        => fn ($q) => $q->where('role', 'owner'),
            'members'      => fn ($q) => $q->where('restaurant_user.role', 'owner'),
            'payments'     => fn ($q) => $q->with('creator')->latest('paid_at'),
            'nfcTags',
        ]);

        // Monthly scans last 3 months
        $scansData = DB::table('nfc_scans')
            ->join('nfc_tags', 'nfc_scans.nfc_tag_id', '=', 'nfc_tags.id')
            ->where('nfc_tags.restaurant_id', $restaurant->id)
            ->where('nfc_scans.date', '>=', now()->subMonths(3)->startOfMonth()->toDateString())
            ->selectRaw("DATE_FORMAT(nfc_scans.date, '%Y-%m') as month, SUM(nfc_scans.count) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Owner last login
        $owner = $restaurant->users->first() ?? $restaurant->members->first();

        return view('superadmin.show', compact('restaurant', 'owner', 'scansData'));
    }

    public function createRestaurant(): View
    {
        $this->ensureSuperAdmin();
        return view('superadmin.create');
    }

    public function storeRestaurant(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'restaurant_name' => ['required', 'string', 'max:255'],
            'restaurant_slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:restaurants,slug',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (in_array(\Illuminate\Support\Str::slug($value), config('reserved_slugs.slugs', []))) {
                        $fail('Este slug está reservado por el sistema.');
                    }
                },
            ],
            'owner_name'     => ['required', 'string', 'max:255'],
            'owner_email'    => ['required', 'email', 'unique:users,email'],
            'owner_password' => ['required', 'string', 'min:8'],
            'plan'           => ['required', 'in:free,basico,pro'],
            'tables'         => ['nullable', 'integer', 'min:0', 'max:50'],
        ]);

        $slug = \Illuminate\Support\Str::slug($validated['restaurant_slug']);

        $restaurant = Restaurant::create([
            'name'          => $validated['restaurant_name'],
            'slug'          => $slug,
            'plan'          => $validated['plan'],
            'trial_ends_at' => $validated['plan'] === 'free' ? null : now()->addDays(14),
            'is_active'     => true,
        ]);

        $owner = User::create([
            'name'          => $validated['owner_name'],
            'email'         => $validated['owner_email'],
            'password'      => Hash::make($validated['owner_password']),
            'restaurant_id' => $restaurant->id,
            'role'          => 'owner',
        ]);

        $restaurant->members()->attach($owner->id, ['role' => 'owner']);

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

        $menuTag = \App\Models\NfcTag::create([
            'code'          => \App\Models\NfcTag::generateCode(),
            'type'          => 'menu',
            'restaurant_id' => $restaurant->id,
            'label'         => 'QR Menú',
            'is_active'     => true,
        ]);

        return redirect()->route('superadmin.credentials', ['restaurant' => $restaurant->id])->with([
            'credentials_restaurant' => $restaurant,
            'credentials_owner'      => $owner,
            'credentials_password'   => $validated['owner_password'],
            'credentials_tags'       => array_merge($tags, [$menuTag]),
        ]);
    }

    public function credentials(Request $request, Restaurant $restaurant): View
    {
        $this->ensureSuperAdmin();
        $data = [
            'restaurant' => session('credentials_restaurant', $restaurant),
            'owner'      => session('credentials_owner'),
            'password'   => session('credentials_password'),
            'tags'       => session('credentials_tags', []),
        ];
        return view('superadmin.credentials', compact('data'));
    }

    public function enterAsRestaurant(Restaurant $restaurant): RedirectResponse
    {
        $this->ensureSuperAdmin();
        session(['superadmin_entry_restaurant_id' => $restaurant->id]);
        return redirect()->route('admin.dashboard');
    }

    public function assignUser(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'role'  => ['required', 'in:owner,staff'],
        ], ['email.exists' => 'No existe un usuario con ese correo.']);

        $user = User::where('email', $validated['email'])->firstOrFail();
        $restaurant->members()->syncWithoutDetaching([$user->id => ['role' => $validated['role']]]);

        return redirect()->back()->with('success', "Usuario {$user->name} asignado como {$validated['role']} en {$restaurant->name}.");
    }

    public function updatePlan(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'plan'                 => ['required', 'in:free,basico,pro'],
            'trial_ends_at'        => ['nullable', 'date'],
            'subscription_ends_at' => ['nullable', 'date'],
            'is_active'            => ['nullable', 'boolean'],
        ]);

        $restaurant->update($validated);

        return redirect()->back()->with('success', 'Plan actualizado.');
    }
}
