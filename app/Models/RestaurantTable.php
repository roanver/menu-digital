<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantTable extends Model
{
    protected $table = 'restaurant_tables';

    protected $fillable = ['restaurant_id', 'name', 'qr_tag_id', 'nfc_tag_id', 'is_active', 'order', 'nfc_chip_written'];

    protected function casts(): array
    {
        return [
            'is_active'        => 'boolean',
            'order'            => 'integer',
            'nfc_chip_written' => 'boolean',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /** Tag permanente del QR (siempre existe, is_physical=false). */
    public function qrTag(): BelongsTo
    {
        return $this->belongsTo(NfcTag::class, 'qr_tag_id');
    }

    /** Chip NFC físico vinculado (nullable). */
    public function nfcTag(): BelongsTo
    {
        return $this->belongsTo(NfcTag::class, 'nfc_tag_id');
    }

    public function qrUrl(): string
    {
        return route('nfc.menu', $this->qrTag->code);
    }

    public function qrScansThisMonth(): int
    {
        return $this->qrTag
            ->scans()
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('count');
    }

    public function nfcScansThisMonth(): int
    {
        if (! $this->nfcTag) {
            return 0;
        }

        return $this->nfcTag
            ->scans()
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('count');
    }

    public function scansThisMonth(): int
    {
        return $this->qrScansThisMonth() + $this->nfcScansThisMonth();
    }
}
