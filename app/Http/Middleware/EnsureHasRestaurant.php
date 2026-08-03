<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasRestaurant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->restaurant_id) {
            return redirect()->route('login')
                ->with('error', 'No tienes un restaurante asociado a tu cuenta.');
        }

        return $next($request);
    }
}
