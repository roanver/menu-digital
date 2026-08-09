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
            $activeId   = session('active_restaurant_id') ?? $user->restaurant_id;
            $restaurant = $activeId ? Restaurant::find($activeId) : null;

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
