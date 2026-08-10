<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\RestaurantTable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name', 'slug', 'type', 'logo', 'address', 'phone', 'whatsapp', 'plan',
    'ai_imports_this_month', 'ai_imports_reset_at',
    'trial_ends_at', 'subscription_ends_at', 'is_active',
    'template', 'primary_color', 'font', 'bg_color',
    'show_price', 'show_description', 'welcome_message',
    'accepts_orders', 'accepts_delivery', 'delivery_zone', 'min_order', 'delivery_cost',
])]
class Restaurant extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'trial_ends_at'          => 'datetime',
            'subscription_ends_at'   => 'datetime',
            'ai_imports_reset_at'    => 'datetime',
            'ai_imports_this_month'  => 'integer',
            'is_active'              => 'boolean',
            'show_price'             => 'boolean',
            'show_description'       => 'boolean',
            'accepts_orders'         => 'boolean',
            'accepts_delivery'       => 'boolean',
            'min_order'              => 'integer',
        ];
    }

    /** Legacy: usuarios vinculados por restaurant_id */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /** Multi-negocio: miembros del equipo con rol */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'restaurant_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class)->orderBy('sort_order');
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    public function nfcTags(): HasMany
    {
        return $this->hasMany(NfcTag::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(RestaurantTable::class)->orderBy('order')->orderBy('id');
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Payment::class)->latest('paid_at');
    }

    public function businessHours(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BusinessHour::class)->orderBy('day_of_week');
    }

    public function isOpenNow(): bool
    {
        $dayOfWeek = (int) now()->dayOfWeek; // 0=Sun…6=Sat
        $hour      = now()->format('H:i:s');

        $bh = $this->businessHours()->where('day_of_week', $dayOfWeek)->first();

        if (! $bh) {
            return true; // sin configurar → asumir abierto
        }
        if ($bh->is_closed) {
            return false;
        }

        $inFirst  = $bh->opens_at  && $bh->closes_at  && $hour >= $bh->opens_at  && $hour < $bh->closes_at;
        $inSecond = $bh->opens_at_2 && $bh->closes_at_2 && $hour >= $bh->opens_at_2 && $hour < $bh->closes_at_2;

        return $inFirst || $inSecond;
    }

    public function nextOpeningTime(): ?string
    {
        $dayOfWeek = (int) now()->dayOfWeek;
        $hour      = now()->format('H:i:s');

        $bh = $this->businessHours()->where('day_of_week', $dayOfWeek)->first();

        if (! $bh || $bh->is_closed) {
            // Look at next day(s)
            for ($i = 1; $i <= 7; $i++) {
                $nextDay = ($dayOfWeek + $i) % 7;
                $next    = $this->businessHours()->where('day_of_week', $nextDay)->first();
                if ($next && ! $next->is_closed && $next->opens_at) {
                    return \App\Models\BusinessHour::dayName($nextDay) . ' ' . substr($next->opens_at, 0, 5);
                }
            }
            return null;
        }

        if ($bh->opens_at && $hour < $bh->opens_at) {
            return 'hoy a las ' . substr($bh->opens_at, 0, 5);
        }
        if ($bh->opens_at_2 && $hour < $bh->opens_at_2) {
            return 'hoy a las ' . substr($bh->opens_at_2, 0, 5);
        }
        return null;
    }

    /** Devuelve la config del vertical activo (config/verticals.php). */
    public function vertical(): array
    {
        return config('verticals.' . ($this->type ?: 'restaurant'), config('verticals.restaurant'));
    }

    public function planIsActive(): bool
    {
        if ($this->plan === 'free') {
            return true;
        }

        return ($this->trial_ends_at && now()->lt($this->trial_ends_at))
            || ($this->subscription_ends_at && now()->lt($this->subscription_ends_at));
    }

    public function planCan(string $feature): bool
    {
        return match ($feature) {
            'unlimited_items'   => in_array($this->plan, ['basico', 'pro']),
            'nfc'               => in_array($this->plan, ['basico', 'pro']),
            'all_templates'     => in_array($this->plan, ['basico', 'pro']),
            'ai_import'         => $this->maxAiImports() !== 0,
            'cart'              => in_array($this->plan, ['basico', 'pro']),
            'ai_advisor'        => $this->plan === 'pro',
            'ai_posts'          => $this->plan === 'pro',
            'full_stats'        => $this->plan === 'pro',
            'staff'             => $this->plan === 'pro',
            'screens'           => $this->plan === 'pro',
            default             => false,
        };
    }

    /** -1 = sin límite, 0 = no permitido, N = N por mes */
    public function maxAiImports(): int
    {
        return (int) config('plans.plans.' . $this->plan . '.ai_imports_per_month', 0);
    }

    public function maxScreens(): int
    {
        return (int) config('plans.plans.' . $this->plan . '.max_screens', 0);
    }
}
