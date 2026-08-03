<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug', 'logo', 'address', 'phone', 'whatsapp', 'plan', 'trial_ends_at', 'subscription_ends_at', 'is_active', 'template', 'primary_color', 'font', 'bg_color', 'show_price', 'show_description', 'welcome_message'])]
class Restaurant extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
            'is_active' => 'boolean',
            'show_price' => 'boolean',
            'show_description' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class)->orderBy('sort_order');
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }
}
