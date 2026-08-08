<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReprocessImages extends Command
{
    protected $signature   = 'menu:reprocess-images {--max-width=800 : Ancho máximo en píxeles}';
    protected $description = 'Reprocesa imágenes ya subidas: redimensiona y re-codifica a WebP.';

    public function handle(): int
    {
        $maxWidth = (int) $this->option('max-width');
        $disk     = storage_path('app/public/images');

        if (! is_dir($disk)) {
            $this->error('Directorio de imágenes no encontrado: ' . $disk);
            return self::FAILURE;
        }

        $files = glob($disk . '/*.webp');
        if (empty($files)) {
            $this->info('No hay imágenes WebP para reprocesar.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        $processed = 0;
        $skipped   = 0;

        foreach ($files as $path) {
            $data  = file_get_contents($path);
            $image = imagecreatefromstring($data);

            if ($image === false) {
                $skipped++;
                $bar->advance();
                continue;
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
                imagewebp($image, $path, 80);
                $processed++;
            } else {
                $skipped++;
            }

            imagedestroy($image);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Procesadas: {$processed} | Sin cambios: {$skipped}");

        return self::SUCCESS;
    }
}
