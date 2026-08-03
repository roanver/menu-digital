<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

abstract class AdminController extends Controller
{
    /**
     * Convert an uploaded image to WebP and save it to storage/app/public/images/.
     * Returns the relative path (images/uuid.webp).
     */
    protected function saveImageAsWebp(UploadedFile $file): string
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $mime = $file->getMimeType();

        if (! in_array($mime, $allowedMimes)) {
            abort(422, 'Tipo de imagen no soportado.');
        }

        $imageData = file_get_contents($file->getPathname());
        $image = imagecreatefromstring($imageData);

        if ($image === false) {
            abort(422, 'No se pudo procesar la imagen.');
        }

        $uuid = (string) Str::uuid();
        $filename = $uuid . '.webp';
        $directory = storage_path('app/public/images');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fullPath = $directory . '/' . $filename;
        imagewebp($image, $fullPath, 85);
        imagedestroy($image);

        return 'images/' . $filename;
    }

    /**
     * Delete an image file from storage given its relative path.
     */
    protected function deleteImageFile(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        $fullPath = storage_path('app/public/' . $relativePath);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
