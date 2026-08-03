<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class EnsurePlanActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->restaurant) {
            $restaurant = $user->restaurant;
            $plan = $restaurant->plan;
            $active = false;

            if ($plan === 'trial') {
                $active = $restaurant->trial_ends_at && Carbon::now()->lt($restaurant->trial_ends_at);
            } elseif ($plan === 'basic' || $plan === 'pro') {
                $active = $restaurant->subscription_ends_at && Carbon::now()->lt($restaurant->subscription_ends_at);
            } elseif ($plan === 'expired') {
                $active = false;
            }

            if (! $active) {
                if ($plan === 'trial') {
                    $message = 'Tu período de prueba ha vencido. Contáctanos para activar tu plan y seguir usando MenuDigital.';
                } elseif ($plan === 'expired') {
                    $message = 'Tu plan ha vencido. Contáctanos para renovar tu suscripción.';
                } else {
                    $message = 'Tu suscripción ha vencido. Contáctanos para renovar y seguir usando MenuDigital.';
                }

                session()->flash('billing_warning', $message);
            }
        }

        return $next($request);
    }
}
