<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Kit extends Model
{
    protected $fillable = [
        'token', 'size', 'type', 'status',
        'restaurant_id', 'activated_at', 'order_reference', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'activated_at' => 'datetime',
            'size'         => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $kit) {
            if (empty($kit->token)) {
                $kit->token = (string) Str::uuid();
            }
        });
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function nfcTags(): HasMany
    {
        return $this->hasMany(NfcTag::class);
    }
}
