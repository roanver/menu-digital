<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentController extends AdminController
{
    private function ensureSuperAdmin(): void
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }
    }

    public function store(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'amount'  => ['required', 'integer', 'min:1'],
            'paid_at' => ['required', 'date'],
            'method'  => ['required', 'in:transferencia,efectivo,otro'],
            'months'  => ['required', 'integer', 'min:1', 'max:24'],
            'notes'   => ['nullable', 'string', 'max:500'],
        ]);

        $payment = Payment::create([
            ...$validated,
            'restaurant_id' => $restaurant->id,
            'created_by'    => auth()->id(),
        ]);

        // Extend subscription_ends_at from current end (or from paid_at if already expired/null)
        $base = $restaurant->subscription_ends_at && $restaurant->subscription_ends_at->isFuture()
            ? $restaurant->subscription_ends_at
            : \Carbon\Carbon::parse($validated['paid_at']);

        $restaurant->update([
            'subscription_ends_at' => $base->addMonths($validated['months']),
            'plan'                 => $restaurant->plan === 'free' ? 'basico' : $restaurant->plan,
        ]);

        return redirect()->back()->with('success', "Pago de $" . number_format($validated['amount'], 0, ',', '.') . " registrado. Suscripción extendida hasta " . $restaurant->fresh()->subscription_ends_at->format('d/m/Y') . ".");
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $this->ensureSuperAdmin();

        if ($payment->cancelled_at) {
            return redirect()->back()->with('error', 'Este pago ya fue anulado.');
        }

        $payment->update(['cancelled_at' => now()]);

        // Revert: subtract months from subscription_ends_at
        $restaurant = $payment->restaurant;
        if ($restaurant->subscription_ends_at) {
            $newDate = $restaurant->subscription_ends_at->subMonths($payment->months);
            $restaurant->update(['subscription_ends_at' => $newDate]);
        }

        return redirect()->back()->with('success', 'Pago anulado y suscripción ajustada.');
    }
}
