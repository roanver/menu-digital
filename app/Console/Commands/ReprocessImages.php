<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ReprocessImages extends Command
{
    protected $signature   = 'menu:reprocess-images {--max-width=800 : Ancho máximo en píxeles}';
    protected $description = 'Reprocesa imágenes ya subidas: redimensiona y re-codifica a WebP.';

    public function handle(): int
    {
        $maxWidth = (int) $this->option('max-width');

        $files = Storage::files('images');
        $webpFiles = array_filter($files, fn ($f) => str_ends_with($f, '.webp'));

        if (empty($webpFiles)) {
            $this->info('No hay imágenes WebP para reprocesar.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar(count($webpFiles));
        $bar->start();

        $processed = 0;
        $skipped   = 0;

        foreach ($webpFiles as $relativePath) {
            $data  = Storage::get($relativePath);
            $image = $data ? imagecreatefromstring($data) : false;

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

                ob_start();
                imagewebp($image, null, 80);
                $webpData = ob_get_clean();

                Storage::put($relativePath, $webpData);
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
