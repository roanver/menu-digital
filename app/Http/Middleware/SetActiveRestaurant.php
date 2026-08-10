<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetActiveRestaurant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || $user->role === 'super_admin') {
            return $next($request);
        }

        $restaurants = $user->restaurants;

        if ($restaurants->isEmpty()) {
            // Sin pivote — usar relación legacy
            if ($user->restaurant_id) {
                session(['active_restaurant_id' => $user->restaurant_id]);
            }
            return $next($request);
        }

        if ($restaurants->count() === 1) {
            session(['active_restaurant_id' => $restaurants->first()->id]);
            return $next($request);
        }

        // Múltiples negocios — verificar selección en sesión
        $activeId = session('active_restaurant_id');

        if ($activeId && $restaurants->pluck('id')->contains($activeId)) {
            return $next($request);
        }

        // Sin selección válida → pantalla de selección
        $skipRoutes = ['admin.restaurants.select', 'admin.restaurants.switch', 'logout', 'profile.*'];
        if (! $request->routeIs(...$skipRoutes)) {
            return redirect()->route('admin.restaurants.select');
        }

        return $next($request);
    }
}
