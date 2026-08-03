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

        User::create([
            'name'          => 'Admin',
            'email'         => 'admin@menudigital.cl',
            'password'      => Hash::make('password'),
            'restaurant_id' => $restaurant->id,
            'role'          => 'owner',
        ]);
    }
}
