<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NfcScan extends Model
{
    protected $fillable = ['nfc_tag_id', 'date', 'count'];

    protected function casts(): array
    {
        return [
            'date'  => 'date',
            'count' => 'integer',
        ];
    }

    public function nfcTag(): BelongsTo
    {
        return $this->belongsTo(NfcTag::class);
    }
}
