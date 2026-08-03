<?php

namespace App\Http\Controllers\Admin;

use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperAdminController extends AdminController
{
    private function authorize(): void
    {
        if (auth()->user()->email !== config('app.super_admin_email')) {
            abort(403);
        }
    }

    public function index(): View
    {
        $this->authorize();

        $restaurants = Restaurant::with(['users' => fn($q) => $q->where('role', 'owner')])
            ->withTrashed()
            ->latest()
            ->get();

        $stats = [
            'total'   => Restaurant::withTrashed()->count(),
            'trial'   => Restaurant::where('plan', 'trial')->count(),
            'paid'    => Restaurant::whereIn('plan', ['basic', 'pro'])->count(),
            'expired' => Restaurant::where('plan', 'expired')->count(),
        ];

        return view('superadmin.index', compact('restaurants', 'stats'));
    }

    public function updatePlan(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $this->authorize();

        $validated = $request->validate([
            'plan'                 => ['required', 'in:trial,basic,pro,expired'],
            'trial_ends_at'        => ['nullable', 'date'],
            'subscription_ends_at' => ['nullable', 'date'],
            'is_active'            => ['boolean'],
        ]);

        $restaurant->update($validated);

        return redirect()->back()->with('success', 'Restaurante actualizado.');
    }
}
