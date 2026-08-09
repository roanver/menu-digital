<?php

namespace App\Http\Controllers\Admin;

use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RestaurantSwitchController extends AdminController
{
    public function select(): View
    {
        $restaurants = auth()->user()->restaurants()->orderBy('name')->get();

        return view('admin.restaurants.select', compact('restaurants'));
    }

    public function switch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'restaurant_id' => ['required', 'integer'],
        ]);

        $restaurantId = (int) $validated['restaurant_id'];

        $belongs = auth()->user()->restaurants()
            ->where('restaurants.id', $restaurantId)
            ->exists();

        if (! $belongs) {
            abort(403);
        }

        session(['active_restaurant_id' => $restaurantId]);

        return redirect()->route('admin.dashboard');
    }

    public function create(): View
    {
        return view('admin.restaurants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:' . implode(',', array_keys(config('verticals', [])))],
        ]);

        $type = $validated['type'] ?? 'restaurant';
        $slug = $this->uniqueSlug($validated['name']);

        $restaurant = Restaurant::create([
            'name'      => $validated['name'],
            'slug'      => $slug,
            'type'      => $type,
            'plan'      => 'free',
            'is_active' => true,
        ]);

        auth()->user()->restaurants()->attach($restaurant->id, ['role' => 'owner']);

        session(['active_restaurant_id' => $restaurant->id]);

        $typeLabel = config('verticals.' . $type . '.label', 'Negocio');

        return redirect()->route('admin.restaurant.edit')
            ->with('success', $typeLabel . ' creado. Completa la información del negocio.');
    }

    public function exitImpersonation(): RedirectResponse
    {
        session()->forget('superadmin_entry_restaurant_id');

        return redirect()->route('superadmin.index');
    }

    private function uniqueSlug(string $name): string
    {
        $base  = Str::slug($name);
        $slug  = $base;
        $count = 2;

        while (Restaurant::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }
}
