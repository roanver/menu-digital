<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Screen extends Model
{
    protected $fillable = [
        'restaurant_id', 'name', 'token', 'category_ids',
        'columns', 'orientation', 'show_images', 'show_promos_rotation', 'is_active',
        'theme', 'accent_color', 'bg_image',
        'density', 'show_descriptions', 'show_out_of_stock', 'out_of_stock_style',
        'show_logo', 'page_seconds', 'footer_message', 'show_qr',
    ];

    protected function casts(): array
    {
        return [
            'category_ids'      => 'array',
            'show_images'       => 'boolean',
            'show_promos_rotation' => 'boolean',
            'is_active'         => 'boolean',
            'show_descriptions' => 'boolean',
            'show_out_of_stock' => 'boolean',
            'show_logo'         => 'boolean',
            'show_qr'           => 'boolean',
            'page_seconds'      => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Screen $screen) {
            $screen->token = Str::random(40);
        });
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
