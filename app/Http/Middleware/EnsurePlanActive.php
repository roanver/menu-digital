<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlanActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->restaurant) {
            $restaurant = $user->restaurant;

            if (!$restaurant->planIsActive()) {
                $message = 'Tu plan ha vencido. Renueva para continuar usando MenuDigital.';

                // Permitir acceso solo a billing y logout
                if (!$request->routeIs('admin.billing.*', 'logout', 'profile.*')) {
                    return redirect()->route('admin.billing.show')
                        ->with('billing_warning', $message);
                }

                session()->flash('billing_warning', $message);
            }
        }

        return $next($request);
    }
}
