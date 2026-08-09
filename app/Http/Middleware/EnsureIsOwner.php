<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Superadmin impersonando — acceso total
        if ($user?->role === 'super_admin') {
            return $next($request);
        }

        if (! $user?->isOwner()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
