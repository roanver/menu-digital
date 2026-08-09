<?php

namespace App\Http\Controllers\Admin;

use App\Services\MenuCacheService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppearanceController extends AdminController
{
    public function edit(): View
    {
        return view('admin.appearance.edit', [
            'restaurant' => $this->restaurant(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'template'          => ['required', 'in:minimal,dark,cards,carta,brasa,feria'],
            'primary_color'     => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'font'              => ['required', 'in:Inter,Poppins,Playfair Display,Pacifico,Oswald'],
            'bg_color'          => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'show_price'        => ['boolean'],
            'show_description'  => ['boolean'],
            'welcome_message'   => ['nullable', 'string', 'max:255'],
        ]);

        $validated['show_price']       = $request->boolean('show_price');
        $validated['show_description'] = $request->boolean('show_description');

        $restaurant = $this->restaurant();
        $restaurant->update($validated);

        MenuCacheService::forget($restaurant);

        return redirect()->route('admin.appearance.edit')
            ->with('success', 'Apariencia guardada correctamente.');
    }
}
