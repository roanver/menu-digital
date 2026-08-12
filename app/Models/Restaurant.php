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

    public function kits(): HasMany
    {
        return $this->hasMany(Kit::class);
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

    private function isInTramo(string $hour, ?string $opens, ?string $closes): bool
    {
        if (!$opens || !$closes) return false;
        if ($closes > $opens) {
            return $hour >= $opens && $hour < $closes;
        }
        // wraps midnight
        return $hour >= $opens || $hour < $closes;
    }

    private function wrapsIntoToday(string $hour, ?string $opens, ?string $closes): bool
    {
        if (!$opens || !$closes || $closes >= $opens) return false;
        return $hour < $closes;
    }

    public function isOpenNow(): bool
    {
        if ($this->businessHours->isEmpty()) return true;

        $now = now('America/Santiago');
        $dayOfWeek = (int) $now->dayOfWeek;
        $hour = $now->format('H:i:s');

        // Check today's schedule
        $bh = $this->businessHours->firstWhere('day_of_week', $dayOfWeek);
        if ($bh && !$bh->is_closed) {
            if ($this->isInTramo($hour, $bh->opens_at, $bh->closes_at)) return true;
            if ($this->isInTramo($hour, $bh->opens_at_2, $bh->closes_at_2)) return true;
        }

        // Check if yesterday's schedule wraps past midnight into now
        $yesterday = ($dayOfWeek + 6) % 7;
        $bhY = $this->businessHours->firstWhere('day_of_week', $yesterday);
        if ($bhY && !$bhY->is_closed) {
            if ($this->wrapsIntoToday($hour, $bhY->opens_at, $bhY->closes_at)) return true;
            if ($this->wrapsIntoToday($hour, $bhY->opens_at_2, $bhY->closes_at_2)) return true;
        }

        // If today has no record but some days do, treat today as open
        if (!$bh) return true;

        return false;
    }

    public function nextOpeningTime(): ?string
    {
        $now = now('America/Santiago');
        $dayOfWeek = (int) $now->dayOfWeek;
        $hour = $now->format('H:i:s');

        $bh = $this->businessHours->firstWhere('day_of_week', $dayOfWeek);

        // If open in first tramo and there's a second tramo later today
        if ($bh && !$bh->is_closed) {
            // between tramos: after closes_at and before opens_at_2
            if ($bh->opens_at_2 && $bh->closes_at_2 && $hour >= $bh->closes_at && $hour < $bh->opens_at_2) {
                return 'a las ' . substr($bh->opens_at_2, 0, 5);
            }
            // before first tramo
            if ($bh->opens_at && $hour < $bh->opens_at) {
                return 'a las ' . substr($bh->opens_at, 0, 5);
            }
        }

        // Look forward up to 7 days
        $dayNames = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
        for ($i = 1; $i <= 7; $i++) {
            $nextDay = ($dayOfWeek + $i) % 7;
            $next = $this->businessHours->firstWhere('day_of_week', $nextDay);
            if ($next && !$next->is_closed && $next->opens_at) {
                $time = 'a las ' . substr($next->opens_at, 0, 5);
                if ($i === 1) return 'mañana ' . $time;
                return 'el ' . $dayNames[$nextDay] . ' ' . $time;
            }
        }

        return null;
    }

    public function hasHoursConfigured(): bool
    {
        return $this->businessHours->isNotEmpty();
    }

    public function closingTimeToday(): ?string
    {
        $now = now('America/Santiago');
        $dayOfWeek = (int) $now->dayOfWeek;
        $hour = $now->format('H:i:s');

        // Check yesterday's midnight-wrap tramos first
        $yesterday = ($dayOfWeek + 6) % 7;
        $bhY = $this->businessHours->firstWhere('day_of_week', $yesterday);
        if ($bhY && !$bhY->is_closed) {
            if ($this->wrapsIntoToday($hour, $bhY->opens_at, $bhY->closes_at))
                return substr($bhY->closes_at, 0, 5);
            if ($this->wrapsIntoToday($hour, $bhY->opens_at_2, $bhY->closes_at_2))
                return substr($bhY->closes_at_2, 0, 5);
        }

        $bh = $this->businessHours->firstWhere('day_of_week', $dayOfWeek);
        if (!$bh || $bh->is_closed) return null;

        if ($this->isInTramo($hour, $bh->opens_at, $bh->closes_at))
            return substr($bh->closes_at, 0, 5);
        if ($this->isInTramo($hour, $bh->opens_at_2, $bh->closes_at_2))
            return substr($bh->closes_at_2, 0, 5);

        return null;
    }

    public function weekScheduleForDisplay(): array
    {
        $now = now('America/Santiago');
        $today = (int) $now->dayOfWeek;
        $names = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $schedule = [];

        for ($d = 0; $d < 7; $d++) {
            $bh = $this->businessHours->firstWhere('day_of_week', $d);
            $tramos = [];
            if ($bh && !$bh->is_closed) {
                if ($bh->opens_at && $bh->closes_at)
                    $tramos[] = [substr($bh->opens_at, 0, 5), substr($bh->closes_at, 0, 5)];
                if ($bh->opens_at_2 && $bh->closes_at_2)
                    $tramos[] = [substr($bh->opens_at_2, 0, 5), substr($bh->closes_at_2, 0, 5)];
            }
            $schedule[] = [
                'day'         => $names[$d],
                'day_of_week' => $d,
                'is_today'    => $d === $today,
                'is_closed'   => $bh ? (bool) $bh->is_closed : false,
                'no_record'   => !$bh,
                'tramos'      => $tramos,
            ];
        }

        return $schedule;
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
