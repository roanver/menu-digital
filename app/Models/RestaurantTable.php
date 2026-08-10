<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantTable extends Model
{
    protected $table = 'restaurant_tables';

    protected $fillable = ['restaurant_id', 'name', 'nfc_tag_id', 'is_active', 'order'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order'     => 'integer',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function nfcTag(): BelongsTo
    {
        return $this->belongsTo(NfcTag::class);
    }

    public function qrUrl(): string
    {
        return route('nfc.menu', $this->nfcTag->code);
    }

    public function scansThisMonth(): int
    {
        return $this->nfcTag
            ->scans()
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('count');
    }
}
