<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function show(string $slug): View
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $categories = $restaurant->categories()
            ->where('is_active', true)
            ->with(['menuItems' => fn ($q) => $q->where('is_available', true)->with('variants')])
            ->get();

        $template = $restaurant->template ?: 'minimal';

        return view("menu.templates.{$template}", compact('restaurant', 'categories'));
    }
}
