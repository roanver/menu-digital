<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class MenuJsonController extends Controller
{
    public function show(string $slug): JsonResponse
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        if (! $restaurant->planIsActive()) {
            abort(404);
        }

        $cacheKey   = "menu:{$slug}";
        $categories = Cache::remember($cacheKey, 3600, function () use ($restaurant) {
            return $restaurant->categories()
                ->where('is_active', true)
                ->with(['menuItems' => fn ($q) => $q->with('variants')->orderBy('sort_order')])
                ->get();
        });

        $data = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Menu',
            'name'        => $restaurant->name . ' — Menú',
            'url'         => url('/' . $restaurant->slug . '/menu.json'),
            'inLanguage'  => 'es',
            'hasMenuSection' => $categories->map(fn ($cat) => [
                '@type'           => 'MenuSection',
                'name'            => $cat->name,
                'hasMenuItem'     => $cat->menuItems->map(fn ($item) => [
                    '@type'           => 'MenuItem',
                    'name'            => $item->name,
                    'description'     => $item->description,
                    'offers'          => [
                        '@type'         => 'Offer',
                        'price'         => $item->is_promo && $item->promo_price ? $item->promo_price : $item->price,
                        'priceCurrency' => 'CLP',
                        'availability'  => $item->is_available
                            ? 'https://schema.org/InStock'
                            : 'https://schema.org/OutOfStock',
                    ],
                    'suitableForDiet' => [],
                ])->values(),
            ])->values(),
        ];

        return response()->json($data)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
