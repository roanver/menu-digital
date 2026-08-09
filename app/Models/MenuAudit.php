<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuAudit extends Model
{
    protected $fillable = ['restaurant_id', 'suggestions', 'score'];

    protected function casts(): array
    {
        return [
            'suggestions' => 'array',
            'score'       => 'integer',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
