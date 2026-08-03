<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (auth()->user()->email === config('app.super_admin_email')) {
            return redirect()->route('superadmin.index');
        }

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Log::info('LOGOUT: iniciando', [
            'user'       => Auth::id(),
            'session_id' => session()->getId(),
            'has_cookie' => $request->hasCookie(Auth::guard('web')->getRecallerName()),
        ]);

        $recallerName = Auth::guard('web')->getRecallerName();

        Auth::guard('web')->logout();
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('LOGOUT: completado', [
            'authenticated_after' => Auth::check(),
            'new_session_id'      => session()->getId(),
        ]);

        return redirect('/')->with('status', 'Sesión cerrada correctamente.')
            ->withoutCookie($recallerName);
    }
}
