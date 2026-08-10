<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlanActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $activeId = session('active_restaurant_id') ?? $user->restaurant_id;
            $restaurant = null;
            if ($activeId) {
                $restaurant = $user->restaurants()->find($activeId);
                // Legacy: sin pivote pero el restaurant_id del usuario coincide
                if (! $restaurant && (int) ($user->restaurant_id ?? 0) === (int) $activeId) {
                    $restaurant = Restaurant::find($activeId);
                }
            }

            if ($restaurant && ! $restaurant->planIsActive()) {
                $message = 'Tu plan ha vencido. Renueva para continuar usando MenuDigital.';

                if (! $request->routeIs('admin.billing.*', 'admin.restaurants.create', 'admin.restaurants.store', 'admin.restaurants.switch', 'logout', 'profile.*')) {
                    return redirect()->route('admin.billing.show')
                        ->with('billing_warning', $message);
                }

                session()->flash('billing_warning', $message);
            }
        }

        return $next($request);
    }
}
