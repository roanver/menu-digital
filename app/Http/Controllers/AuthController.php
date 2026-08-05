<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Credenciales incorrectas.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (auth()->user()->email === config('app.super_admin_email')) {
            return redirect()->route('superadmin.index');
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password'         => ['required', 'confirmed', 'min:8'],
            'restaurant_name'  => ['required', 'string', 'max:255'],
            'restaurant_slug'  => ['nullable', 'string', 'max:255', 'alpha_dash', 'unique:restaurants,slug'],
        ]);

        $slug = $request->filled('restaurant_slug')
            ? Str::slug($request->restaurant_slug)
            : Str::slug($request->restaurant_name);

        $base = $slug;
        $i = 1;
        while (Restaurant::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        $restaurant = Restaurant::create([
            'name'          => $request->restaurant_name,
            'slug'          => $slug,
            'plan'          => 'carta',
            'trial_ends_at' => now()->addDays(14),
            'is_active'     => true,
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'restaurant_id' => $restaurant->id,
            'role'          => 'owner',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
