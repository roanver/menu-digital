<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Convert an uploaded image to WebP, resize to maxWidth, and store it.
     * Returns the relative path (images/uuid.webp) for use with Storage::url().
     */
    public static function saveAsWebp(UploadedFile $file, int $maxWidth = 800): string
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

        $origW = imagesx($image);
        $origH = imagesy($image);

        if ($origW > $maxWidth) {
            $newH    = (int) round($origH * $maxWidth / $origW);
            $resized = imagecreatetruecolor($maxWidth, $newH);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $maxWidth, $newH, $origW, $origH);
            imagedestroy($image);
            $image = $resized;
        }

        ob_start();
        imagewebp($image, null, 80);
        $webpData = ob_get_clean();
        imagedestroy($image);

        $path = 'images/' . ((string) Str::uuid()) . '.webp';
        Storage::put($path, $webpData);

        return $path;
    }

    /**
     * Delete an image from storage given its relative path (e.g. "images/uuid.webp").
     */
    public static function delete(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        Storage::delete($relativePath);
    }
}
