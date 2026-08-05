<?php

namespace App\Services;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Cache;

class MenuCacheService
{
    public static function forget(Restaurant $restaurant): void
    {
        Cache::forget("menu:{$restaurant->slug}");
    }
}
