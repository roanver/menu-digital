<?php

namespace App\Http\Controllers\Admin;

use App\Services\MenuCacheService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestaurantController extends AdminController
{
    public function edit(): View
    {
        $restaurant = $this->restaurant();

        return view('admin.restaurant.edit', compact('restaurant'));
    }

    public function update(Request $request): RedirectResponse
    {
        $restaurant = $this->restaurant();

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'type'             => ['nullable', 'string', 'in:' . implode(',', array_keys(config('verticals', [])))],
            'address'          => ['nullable', 'string', 'max:500'],
            'phone'            => ['nullable', 'string', 'max:50'],
            'whatsapp'         => ['nullable', 'string', 'max:50'],
            'logo'             => ['nullable', 'image', 'max:2048'],
            'accepts_orders'   => ['boolean'],
            'accepts_delivery' => ['boolean'],
            'delivery_zone'    => ['nullable', 'string', 'max:1000'],
            'min_order'        => ['nullable', 'integer', 'min:0'],
            'delivery_cost'    => ['nullable', 'integer', 'min:0'],
        ], [
            'logo.max'   => 'El logo no debe superar los 2 MB.',
            'logo.image' => 'El logo debe ser una imagen (jpg, png, webp).',
        ]);

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $oldLogo = $restaurant->logo;
            $validated['logo'] = $this->saveImageAsWebp($request->file('logo'), 400);
            $this->deleteImageFile($oldLogo);
        } else {
            unset($validated['logo']);
        }

        $validated['accepts_orders']   = $request->boolean('accepts_orders');
        $validated['accepts_delivery'] = $request->boolean('accepts_delivery');

        $originalSlug = $restaurant->slug;

        $restaurant->update($validated);

        MenuCacheService::forgetSlug($originalSlug);
        if ($restaurant->slug !== $originalSlug) {
            MenuCacheService::forgetSlug($restaurant->slug);
        }

        $typeLabel = config('verticals.' . ($restaurant->type ?: 'restaurant') . '.label', 'Negocio');

        return redirect()->route('admin.restaurant.edit')
            ->with('success', $typeLabel . ' actualizado correctamente.');
    }
}
