<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{
    public function show(string $slug): mixed
    {
        $cacheKey = "menu:{$slug}";

        $data = Cache::remember($cacheKey, 3600, function () use ($slug) {
            $restaurant = Restaurant::where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail();

            // Verificar gracia de 7 días post-vencimiento
            if (!$restaurant->planIsActive()) {
                $grace = $restaurant->subscription_ends_at ?? $restaurant->trial_ends_at;
                if (!$grace || now()->gt($grace->addDays(7))) {
                    abort(404);
                }
            }

            $categories = $restaurant->categories()
                ->where('is_active', true)
                ->with(['menuItems' => fn ($q) => $q->with('variants')->orderBy('sort_order')])
                ->get();

            return compact('restaurant', 'categories');
        });

        $restaurant = $data['restaurant'];
        $categories = $data['categories'];
        $template = $restaurant->template ?: 'minimal';

        return view("menu.templates.{$template}", compact('restaurant', 'categories'));
    }
}
