<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{
    public function show(string $slug): mixed
    {
        // Estado del negocio: NO se cachea — refleja el estado en vivo
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();

        if (! $restaurant->is_active) {
            abort(404);
        }

        if (! $restaurant->planIsActive()) {
            $grace = $restaurant->subscription_ends_at ?? $restaurant->trial_ends_at;
            if (! $grace || now()->gt($grace->addDays(7))) {
                abort(404);
            }
        }

        // Contenido del menú: cacheado (categorías + items + variantes)
        $cacheKey   = "menu:{$slug}";
        $categories = Cache::remember($cacheKey, 3600, function () use ($restaurant) {
            return $restaurant->categories()
                ->where('is_active', true)
                ->with(['menuItems' => fn ($q) => $q->with('variants')->orderBy('sort_order')])
                ->get();
        });

        // Estado abierto/cerrado: NO se cachea (cambia cada minuto)
        $restaurant->loadMissing('businessHours');
        $isOpen      = $restaurant->isOpenNow();
        $nextOpening = $isOpen ? null : $restaurant->nextOpeningTime();

        $template = $restaurant->template ?: 'minimal';

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type'    => 'Restaurant',
            'name'     => $restaurant->name,
            'url'      => url('/' . $restaurant->slug),
            'menu'     => url('/' . $restaurant->slug . '/menu.json'),
            'servesCuisine' => [],
            'hasMap'   => $restaurant->address ? 'https://maps.google.com/?q=' . urlencode($restaurant->address) : null,
            'telephone' => $restaurant->phone,
            'address'  => $restaurant->address ? [
                '@type'           => 'PostalAddress',
                'streetAddress'   => $restaurant->address,
                'addressCountry'  => 'CL',
            ] : null,
        ];

        return view("menu.templates.{$template}", compact('restaurant', 'categories', 'isOpen', 'nextOpening', 'jsonLd'));
    }
}
