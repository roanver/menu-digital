<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestaurantController extends AdminController
{
    public function edit(): View
    {
        $restaurant = auth()->user()->restaurant;

        return view('admin.restaurant.edit', compact('restaurant'));
    }

    public function update(Request $request): RedirectResponse
    {
        $restaurant = auth()->user()->restaurant;

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'address'   => ['nullable', 'string', 'max:500'],
            'phone'     => ['nullable', 'string', 'max:50'],
            'whatsapp'  => ['nullable', 'string', 'max:50'],
            'logo'      => ['nullable', 'image', 'max:2048'],
        ], [
            'logo.max'   => 'El logo no debe superar los 2 MB.',
            'logo.image' => 'El logo debe ser una imagen (jpg, png, webp).',
        ]);

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $oldLogo = $restaurant->logo;
            $validated['logo'] = $this->saveImageAsWebp($request->file('logo'));
            $this->deleteImageFile($oldLogo);
        } else {
            unset($validated['logo']);
        }

        $restaurant->update($validated);

        return redirect()->route('admin.restaurant.edit')
            ->with('success', 'Restaurante actualizado correctamente.');
    }
}
