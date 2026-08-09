<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'restaurant_id', 'category_id', 'name', 'sku', 'description', 'price',
    'image', 'is_available', 'stock', 'sort_order', 'is_promo', 'promo_price',
])]
class MenuItem extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'price'        => 'integer',
            'is_available' => 'boolean',
            'stock'        => 'integer',
            'is_promo'     => 'boolean',
            'promo_price'  => 'integer',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ItemVariant::class)->orderBy('sort_order');
    }
}
