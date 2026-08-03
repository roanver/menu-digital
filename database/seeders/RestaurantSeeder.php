<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ItemVariant;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::create([
            'name'     => 'La Buena Mesa',
            'slug'     => 'la-buena-mesa',
            'address'  => 'Av. Providencia 1234, Santiago',
            'phone'    => '+56 2 2345 6789',
            'whatsapp' => '56912345678',
            'plan'     => 'trial',
            'is_active' => true,
        ]);

        // Categoria 1: Entradas
        $entradas = Category::create([
            'restaurant_id' => $restaurant->id,
            'name'          => 'Entradas',
            'sort_order'    => 1,
            'is_active'     => true,
        ]);

        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id'   => $entradas->id,
            'name'          => 'Empanadas de pino',
            'description'   => 'Empanadas horneadas rellenas de carne, cebolla, huevo y aceitunas. Masa crujiente y dorada.',
            'price'         => 2500,
            'is_available'  => true,
            'sort_order'    => 1,
        ]);

        $bruschetta = MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id'   => $entradas->id,
            'name'          => 'Bruschetta',
            'description'   => 'Pan tostado con tomate fresco, ajo y albahaca.',
            'price'         => 3200,
            'is_available'  => true,
            'sort_order'    => 2,
        ]);

        ItemVariant::create(['menu_item_id' => $bruschetta->id, 'name' => 'Individual', 'price_delta' => 0,    'sort_order' => 1]);
        ItemVariant::create(['menu_item_id' => $bruschetta->id, 'name' => 'Para compartir (x3)', 'price_delta' => 5800, 'sort_order' => 2]);

        // Categoria 2: Platos de fondo
        $fondos = Category::create([
            'restaurant_id' => $restaurant->id,
            'name'          => 'Platos de fondo',
            'sort_order'    => 2,
            'is_active'     => true,
        ]);

        $lomo = MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id'   => $fondos->id,
            'name'          => 'Lomo a lo pobre',
            'description'   => 'Filete de vacuno a la plancha con papas fritas, huevos fritos y cebolla caramelizada.',
            'price'         => 9900,
            'is_available'  => true,
            'sort_order'    => 1,
        ]);

        ItemVariant::create(['menu_item_id' => $lomo->id, 'name' => 'Término medio',  'price_delta' => 0,   'sort_order' => 1]);
        ItemVariant::create(['menu_item_id' => $lomo->id, 'name' => 'Bien cocido',    'price_delta' => 0,   'sort_order' => 2]);
        ItemVariant::create(['menu_item_id' => $lomo->id, 'name' => 'Con extra queso','price_delta' => 1500,'sort_order' => 3]);

        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id'   => $fondos->id,
            'name'          => 'Pastel de choclo',
            'description'   => 'Clásico pastel chileno de choclo dulce con pino de carne y pollo.',
            'price'         => 8500,
            'is_available'  => true,
            'sort_order'    => 2,
        ]);

        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id'   => $fondos->id,
            'name'          => 'Salmón a la plancha',
            'description'   => 'Filete de salmón fresco con ensalada de quinoa y limón.',
            'price'         => 11900,
            'is_available'  => true,
            'sort_order'    => 3,
        ]);

        // Categoria 3: Bebidas
        $bebidas = Category::create([
            'restaurant_id' => $restaurant->id,
            'name'          => 'Bebidas',
            'sort_order'    => 3,
            'is_active'     => true,
        ]);

        $jugo = MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id'   => $bebidas->id,
            'name'          => 'Jugo natural',
            'description'   => 'Jugo exprimido al momento.',
            'price'         => 2800,
            'is_available'  => true,
            'sort_order'    => 1,
        ]);

        ItemVariant::create(['menu_item_id' => $jugo->id, 'name' => 'Naranja',  'price_delta' => 0,   'sort_order' => 1]);
        ItemVariant::create(['menu_item_id' => $jugo->id, 'name' => 'Mango',    'price_delta' => 200, 'sort_order' => 2]);
        ItemVariant::create(['menu_item_id' => $jugo->id, 'name' => 'Piña',     'price_delta' => 0,   'sort_order' => 3]);

        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id'   => $bebidas->id,
            'name'          => 'Agua mineral',
            'price'         => 1200,
            'is_available'  => true,
            'sort_order'    => 2,
        ]);

        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id'   => $bebidas->id,
            'name'          => 'Bebida en lata',
            'description'   => 'Coca-Cola, Sprite o Fanta.',
            'price'         => 1500,
            'is_available'  => true,
            'sort_order'    => 3,
        ]);
    }
}
