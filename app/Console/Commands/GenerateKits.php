<?php

namespace App\Console\Commands;

use App\Models\Kit;
use App\Models\NfcTag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateKits extends Command
{
    protected $signature = 'kits:generate
        {count : Cantidad de kits a generar}
        {--size=4 : Mesas por kit}
        {--type=mesas : Tipo de kit (mesas|resenas)}
        {--reviews : Agrega un tag de reseñas por kit}';

    protected $description = 'Genera kits de chips en lote, sin asignar restaurante';

    public function handle(): int
    {
        $count   = (int) $this->argument('count');
        $size    = (int) $this->option('size');
        $type    = $this->option('type');
        $reviews = (bool) $this->option('reviews');

        if (! in_array($type, ['mesas', 'resenas'])) {
            $this->error("Tipo inválido: '{$type}'. Usá mesas o resenas.");
            return self::FAILURE;
        }

        if ($count < 1 || $size < 1) {
            $this->error('count y --size deben ser >= 1.');
            return self::FAILURE;
        }

        $tagsPerKit = ($size * 2) + ($reviews ? 1 : 0);
        $this->info("Generando {$count} kits × {$size} mesas = {$tagsPerKit} tags por kit…");

        $rows = [];

        DB::transaction(function () use ($count, $size, $type, $reviews, &$rows) {
            for ($k = 1; $k <= $count; $k++) {
                $kit = Kit::create([
                    'size'   => $size,
                    'type'   => $type,
                    'status' => 'generado',
                ]);

                for ($m = 1; $m <= $size; $m++) {
                    NfcTag::createTablePair(
                        restaurantId: null,
                        label:        "Mesa {$m}",
                        kitId:        $kit->id,
                        slotLabel:    "Mesa {$m}",
                    );
                }

                if ($reviews) {
                    NfcTag::create([
                        'code'          => NfcTag::generateCode(),
                        'type'          => 'review',
                        'restaurant_id' => null,
                        'label'         => 'Reseñas',
                        'is_active'     => true,
                        'is_physical'   => false,
                        'kit_id'        => $kit->id,
                        'slot_label'    => 'Reseñas',
                    ]);
                }

                $rows[] = [
                    substr($kit->token, 0, 18) . '…',
                    $size,
                    $kit->nfcTags()->count(),
                    $kit->status,
                ];

                $this->output->write("\r  Kit {$k}/{$count}…");
            }
        });

        $this->newLine();
        $this->table(
            ['Token (abrev.)', 'Mesas', 'Tags', 'Estado'],
            $rows,
        );

        $this->newLine();
        $this->info('5 ejemplos de códigos generados:');
        for ($i = 0; $i < 5; $i++) {
            $this->line('  ' . NfcTag::generateCode());
        }

        $this->newLine();
        $this->info("Listo. {$count} kits grabados en la base.");

        return self::SUCCESS;
    }
}
