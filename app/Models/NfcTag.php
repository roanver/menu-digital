<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class NfcTag extends Model
{
    protected $fillable = [
        'code', 'restaurant_id', 'type', 'label',
        'target_url', 'scans_count', 'last_scanned_at', 'is_active', 'is_physical',
        'kit_id', 'slot_label',
    ];

    protected function casts(): array
    {
        return [
            'is_active'       => 'boolean',
            'is_physical'     => 'boolean',
            'scans_count'     => 'integer',
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

    public function table(): HasOne
    {
        return $this->hasOne(RestaurantTable::class);
    }

    public function kit(): BelongsTo
    {
        return $this->belongsTo(Kit::class);
    }

    public function scopeFreeChips($query)
    {
        return $query->where('is_physical', true)->whereDoesntHave('table');
    }

    public static function generateCode(): string
    {
        $alphabet = '23456789ABCDEFGHJKMNPQRSTUVWXYZ'; // 31 chars — sin O/0/I/1/L

        do {
            $code = '';
            while (strlen($code) < 8) {
                $byte = ord(random_bytes(1));
                if ($byte < 248) { // 31 × 8 = 248 — sin modulo bias
                    $code .= $alphabet[$byte % 31];
                }
            }
        } while (static::where('code', $code)->exists());

        return $code;
    }

    /**
     * Crea el par QR + NFC para una mesa. Centraliza la lógica que estaba
     * duplicada en TableController::store() y storeBulk(), y que también
     * usa kits:generate.
     *
     * @return array{qr: self, nfc: self}
     */
    public static function createTablePair(
        ?int $restaurantId,
        string $label,
        ?int $kitId = null,
        ?string $slotLabel = null
    ): array {
        $shared = [
            'type'          => 'menu',
            'restaurant_id' => $restaurantId,
            'label'         => $label,
            'is_active'     => true,
            'is_physical'   => false,
            'kit_id'        => $kitId,
            'slot_label'    => $slotLabel,
        ];

        $qr  = static::create(['code' => static::generateCode()] + $shared);
        $nfc = static::create(['code' => static::generateCode()] + $shared);

        return ['qr' => $qr, 'nfc' => $nfc];
    }
}
