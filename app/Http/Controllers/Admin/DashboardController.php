<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\View\View;

class DashboardController extends AdminController
{
    public function index(): View
    {
        $restaurant = auth()->user()->restaurant;

        $categoriesCount = Category::where('restaurant_id', $restaurant->id)->count();
        $itemsCount = MenuItem::where('restaurant_id', $restaurant->id)->count();

        return view('admin.dashboard', compact('restaurant', 'categoriesCount', 'itemsCount'));
    }
}
