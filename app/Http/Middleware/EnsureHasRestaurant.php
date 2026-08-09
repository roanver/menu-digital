<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasRestaurant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect('/');
        }

        if ($user->role === 'super_admin') {
            return redirect()->route('superadmin.index');
        }

        // Tiene negocios en el pivote o en la relación legacy
        $hasRestaurant = $user->restaurants()->exists() || $user->restaurant_id;

        if (! $hasRestaurant) {
            return redirect('/')->with('error', 'No tienes un restaurante asociado a tu cuenta.');
        }

        return $next($request);
    }
}
