<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <hr class="my-6 border-gray-200">

        <!-- Restaurant Name -->
        <div>
            <x-input-label for="restaurant_name" :value="__('Nombre del Restaurante')" />
            <x-text-input id="restaurant_name" class="block mt-1 w-full" type="text" name="restaurant_name" :value="old('restaurant_name')" required />
            <x-input-error :messages="$errors->get('restaurant_name')" class="mt-2" />
        </div>

        <!-- Restaurant Slug -->
        <div class="mt-4">
            <x-input-label for="restaurant_slug" :value="__('URL del Menú (opcional)')" />
            <div class="flex items-center mt-1">
                <span class="text-gray-500 text-sm mr-1">{{ config('app.url') }}/</span>
                <x-text-input id="restaurant_slug" class="block w-full" type="text" name="restaurant_slug" :value="old('restaurant_slug')" placeholder="mi-restaurante" />
            </div>
            <p class="mt-1 text-xs text-gray-500">Se genera automáticamente si lo dejas vacío.</p>
            <x-input-error :messages="$errors->get('restaurant_slug')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('¿Ya tienes cuenta?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
