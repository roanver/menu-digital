<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Screen;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class BoardController extends Controller
{
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

        // Compute board colors
        $bgColor     = $this->ensureDark($restaurant->bg_color ?? '#0F1012');
        $accentColor = $restaurant->primary_color ?? '#FFFFFF';
        $textColor   = '#F2F2F0';

        return view('board.show', compact(
            'screen', 'restaurant', 'categories', 'promos',
            'gracePeriod', 'bgColor', 'accentColor', 'textColor'
        ));
    }

    public function version(string $token): JsonResponse
    {
        $screen = Screen::where('token', $token)
            ->select(['id', 'restaurant_id', 'category_ids', 'updated_at'])
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

    /** Force a hex color to be dark enough for TV display. */
    private function ensureDark(string $hex): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) !== 6) return '#0F1012';

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        // If color is too light, use a dark default
        return $luminance > 0.45 ? '#0F1012' : '#' . $hex;
    }
}
