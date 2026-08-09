<?php

namespace App\Console\Commands;

use App\Models\Restaurant;
use Illuminate\Console\Command;

class CheckReservedSlugs extends Command
{
    protected $signature   = 'slugs:check';
    protected $description = 'Detecta negocios existentes cuyo slug colisiona con rutas reservadas del sistema.';

    public function handle(): int
    {
        $reserved = config('reserved_slugs.slugs');
        $conflicts = Restaurant::whereIn('slug', $reserved)->get(['id', 'name', 'slug']);

        if ($conflicts->isEmpty()) {
            $this->info('Sin conflictos. Ningún slug existente colisiona con rutas del sistema.');
            return self::SUCCESS;
        }

        $this->error('Se encontraron ' . $conflicts->count() . ' conflicto(s):');
        $this->table(['ID', 'Nombre', 'Slug'], $conflicts->map(fn ($r) => [$r->id, $r->name, $r->slug]));
        $this->warn('Estos negocios deben cambiar su slug antes de continuar.');

        return self::FAILURE;
    }
}
