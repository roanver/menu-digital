<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'        => ['required', 'confirmed', Rules\Password::defaults()],
            'restaurant_name' => ['required', 'string', 'max:255'],
            'restaurant_slug' => ['nullable', 'string', 'max:255', 'alpha_dash', 'unique:restaurants,slug'],
        ]);

        // Generate slug from restaurant name if not provided
        $slug = $request->filled('restaurant_slug')
            ? Str::slug($request->restaurant_slug)
            : Str::slug($request->restaurant_name);

        // Ensure uniqueness
        $originalSlug = $slug;
        $counter = 1;
        while (Restaurant::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $restaurant = Restaurant::create([
            'name'          => $request->restaurant_name,
            'slug'          => $slug,
            'plan'          => 'trial',
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

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('admin.dashboard', absolute: false));
    }
}
