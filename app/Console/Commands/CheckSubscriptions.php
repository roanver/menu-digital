<?php

namespace App\Console\Commands;

use App\Models\Restaurant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CheckSubscriptions extends Command
{
    protected $signature = 'menu:check-subscriptions';
    protected $description = 'Limpia cache de restaurantes con plan vencido y reporta el estado';

    public function handle(): void
    {
        $expired = Restaurant::where('is_active', true)
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->whereNotNull('subscription_ends_at')
                       ->where('subscription_ends_at', '<', now());
                })->orWhere(function ($q2) {
                    $q2->whereNull('subscription_ends_at')
                       ->whereNotNull('trial_ends_at')
                       ->where('trial_ends_at', '<', now()->subDays(7));
                });
            })
            ->get();

        foreach ($expired as $restaurant) {
            Cache::forget("menu:{$restaurant->slug}");
        }

        $this->info("Revisados. {$expired->count()} restaurantes con plan vencido.");
    }
}
