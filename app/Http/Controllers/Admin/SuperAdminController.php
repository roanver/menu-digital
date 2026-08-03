<?php

namespace App\Http\Controllers\Admin;

use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperAdminController extends AdminController
{
    public function index(): View
    {
        if (auth()->user()->email !== config('app.super_admin_email')) {
            abort(403);
        }

        $restaurants = Restaurant::with('users')->withTrashed()->get();

        return view('superadmin.index', compact('restaurants'));
    }

    public function updatePlan(Request $request, Restaurant $restaurant): RedirectResponse
    {
        if (auth()->user()->email !== config('app.super_admin_email')) {
            abort(403);
        }

        $validated = $request->validate([
            'plan'                 => ['required', 'in:trial,basic,pro,expired'],
            'subscription_ends_at' => ['nullable', 'date'],
        ]);

        $restaurant->update($validated);

        return redirect()->back()->with('success', 'Plan actualizado correctamente.');
    }
}
