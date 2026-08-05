<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class NfcTag extends Model
{
    protected $fillable = [
        'code', 'restaurant_id', 'type', 'label',
        'target_url', 'scans_count', 'last_scanned_at', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active'      => 'boolean',
            'scans_count'    => 'integer',
            'last_scanned_at' => 'datetime',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function scans(): HasMany
    {
        return $this->hasMany(NfcScan::class);
    }

    public static function generateCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
