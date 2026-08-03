<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RestaurantSeeder::class);

        $restaurant = Restaurant::where('slug', 'la-buena-mesa')->first();

        // Dueño del restaurante de prueba
        User::create([
            'name'          => 'Dueño Demo',
            'email'         => 'dueno@labuenamesa.cl',
            'password'      => Hash::make('password'),
            'restaurant_id' => $restaurant->id,
            'role'          => 'owner',
        ]);

        // Super admin del sistema (sin restaurant_id)
        // El email debe coincidir con SUPER_ADMIN_EMAIL en .env
        User::create([
            'name'     => 'Super Admin',
            'email'    => config('app.super_admin_email') ?: 'superadmin@menudigital.cl',
            'password' => Hash::make('password'),
        ]);
    }
}
