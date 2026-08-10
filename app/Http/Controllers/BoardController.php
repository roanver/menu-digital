<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Screen;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BoardController extends Controller
{
    private const THEMES = [
        'dark'  => ['bg' => '#111115', 'text' => '#F2F2F0'],
        'warm'  => ['bg' => '#1C0F08', 'text' => '#F5ECD7'],
        'slate' => ['bg' => '#0F172A', 'text' => '#E2E8F0'],
        'chalk' => ['bg' => '#1E1B18', 'text' => '#F7F4F0'],
        'neon'  => ['bg' => '#080812', 'text' => '#F0F0FF'],
    ];

    public function show(string $token): View
    {
        $screen = Screen::where('token', $token)
            ->with('restaurant')
            ->firstOrFail();

        abort_if(! $screen->is_active, 404);

        $restaurant = $screen->restaurant;

        $categories = $this->loadCategories($screen, $restaurant->id);

        $promos = $categories->flatMap(fn ($c) => $c->menuItems)
            ->filter(fn ($item) => $item->is_promo && $item->promo_price && $item->is_available)
            ->values();

        // 7-day grace after plan expiry so screens don't go dark instantly
        $gracePeriod = $restaurant->planIsActive()
            || ($restaurant->subscription_ends_at
                && now()->lt($restaurant->subscription_ends_at->addDays(7)));

        $theme       = self::THEMES[$screen->theme ?? 'dark'] ?? self::THEMES['dark'];
        $bgColor     = $theme['bg'];
        $textColor   = $theme['text'];
        $accentColor = $screen->accent_color ?? $restaurant->primary_color ?? '#6366F1';
        $bgImage     = $screen->bg_image ? Storage::url($screen->bg_image) : null;

        return view('board.show', compact(
            'screen', 'restaurant', 'categories', 'promos',
            'gracePeriod', 'bgColor', 'accentColor', 'textColor', 'bgImage'
        ));
    }

    public function version(string $token): JsonResponse
    {
        $screen = Screen::where('token', $token)
            ->select(['id', 'restaurant_id', 'category_ids', 'updated_at', 'is_active'])
            ->firstOrFail();

        if (! $screen->is_active) {
            return response()->json(['hash' => 'inactive'])
                ->header('Cache-Control', 'no-store');
        }

        $restaurantUpdated = $screen->restaurant()->value('updated_at');

        $catIds = $screen->category_ids;

        $itemsMax = MenuItem::where('restaurant_id', $screen->restaurant_id)
            ->when($catIds, fn ($q) => $q->whereIn('category_id', $catIds))
            ->max('updated_at');

        $catsMax = Category::where('restaurant_id', $screen->restaurant_id)
            ->when($catIds, fn ($q) => $q->whereIn('id', $catIds))
            ->max('updated_at');

        $hash = md5(implode('|', [
            $screen->updated_at,
            $restaurantUpdated,
            $itemsMax,
            $catsMax,
        ]));

        return response()->json(['hash' => $hash])
            ->header('Cache-Control', 'no-store, no-cache');
    }

    private function loadCategories(Screen $screen, int $restaurantId)
    {
        return Category::where('restaurant_id', $restaurantId)
            ->when($screen->category_ids, fn ($q) => $q->whereIn('id', $screen->category_ids))
            ->with(['menuItems' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
