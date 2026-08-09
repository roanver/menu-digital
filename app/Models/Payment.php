<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'restaurant_id', 'amount', 'paid_at', 'method',
        'months', 'notes', 'created_by', 'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_at'      => 'date',
            'cancelled_at' => 'datetime',
            'amount'       => 'integer',
            'months'       => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->whereNull('cancelled_at');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
