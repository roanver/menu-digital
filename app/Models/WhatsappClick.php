<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappClick extends Model
{
    protected $fillable = ['restaurant_id', 'date', 'type', 'count'];

    protected function casts(): array
    {
        return [
            'date'  => 'date',
            'count' => 'integer',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
