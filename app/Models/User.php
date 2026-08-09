<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'restaurant_id', 'role', 'last_login_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /** Relación legacy — un usuario, un restaurante (mantener hasta confirmar pivote OK) */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /** Relación multi-negocio — todos los restaurantes del usuario con su rol */
    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * ¿El usuario es owner del negocio activo en sesión?
     * Usa el pivote si existe; hace fallback al campo users.role para la transición.
     */
    public function isOwner(): bool
    {
        $activeId = session('active_restaurant_id');
        if ($activeId) {
            $member = $this->restaurants()->where('restaurants.id', $activeId)->first();
            if ($member) {
                return $member->pivot->role === 'owner';
            }
        }
        return $this->role === 'owner';
    }

    public function isStaff(): bool
    {
        $activeId = session('active_restaurant_id');
        if ($activeId) {
            $member = $this->restaurants()->where('restaurants.id', $activeId)->first();
            if ($member) {
                return $member->pivot->role === 'staff';
            }
        }
        return $this->role === 'staff';
    }
}
