<?php

namespace App\Http\Controllers\Admin;

use App\Models\Screen;
use Illuminate\View\View;

class BillingController extends AdminController
{
    public function show(): View
    {
        $restaurant = $this->restaurant();

        $itemCount      = $restaurant->menuItems()->count();
        $categoryCount  = $restaurant->categories()->count();
        $screenCount    = Screen::where('restaurant_id', $restaurant->id)->count();
        $nfcActiveCount = $restaurant->nfcTags()->where('is_active', true)->count();

        return view('admin.billing.show', compact(
            'restaurant',
            'itemCount',
            'categoryCount',
            'screenCount',
            'nfcActiveCount',
        ));
    }
}
