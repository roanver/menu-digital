<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\ImageService;
use Illuminate\Http\UploadedFile;

abstract class AdminController extends Controller
{
    protected ?Restaurant $restaurant = null;

    protected function restaurant(): Restaurant
    {
        if ($this->restaurant) {
            return $this->restaurant;
        }

        // Superadmin impersonando un negocio
        if (auth()->user()?->role === 'super_admin') {
            $id = session('superadmin_entry_restaurant_id');
            if ($id) {
                return $this->restaurant = Restaurant::findOrFail($id);
            }
        }

        // Negocio activo en sesión (multi-negocio)
        $activeId = session('active_restaurant_id');
        if ($activeId) {
            $user = auth()->user();

            // Verificar pertenencia por pivote
            $restaurant = $user->restaurants()->find($activeId);
            if ($restaurant) {
                return $this->restaurant = $restaurant;
            }

            // Legacy: sin entrada en pivote, pero el restaurant_id del usuario coincide
            if ((int) ($user->restaurant_id ?? 0) === (int) $activeId) {
                return $this->restaurant = Restaurant::findOrFail($activeId);
            }

            // active_restaurant_id no pertenece a este usuario
            abort(403);
        }

        // Sin sesión activa: fallback legacy
        return $this->restaurant = auth()->user()->restaurant;
    }

    protected function saveImageAsWebp(UploadedFile $file, int $maxWidth = 800): string
    {
        return ImageService::saveAsWebp($file, $maxWidth);
    }

    protected function deleteImageFile(?string $relativePath): void
    {
        ImageService::delete($relativePath);
    }
}
