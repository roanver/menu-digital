<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Expand enum con valores viejos + nuevos
        DB::statement("ALTER TABLE restaurants MODIFY COLUMN plan ENUM('carta','pedidos','full','free','basico','pro') NOT NULL DEFAULT 'carta'");

        // 2. Migrar datos
        DB::statement("UPDATE restaurants SET plan = 'pro'    WHERE plan = 'full'");
        DB::statement("UPDATE restaurants SET plan = 'basico' WHERE plan IN ('carta','pedidos')");

        // 3. Restringir a valores nuevos y cambiar default
        DB::statement("ALTER TABLE restaurants MODIFY COLUMN plan ENUM('free','basico','pro') NOT NULL DEFAULT 'free'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE restaurants MODIFY COLUMN plan ENUM('free','basico','pro','carta','pedidos','full') NOT NULL DEFAULT 'free'");
        DB::statement("UPDATE restaurants SET plan = 'full'  WHERE plan = 'pro'");
        DB::statement("UPDATE restaurants SET plan = 'carta' WHERE plan IN ('basico','free')");
        DB::statement("ALTER TABLE restaurants MODIFY COLUMN plan ENUM('carta','pedidos','full') NOT NULL DEFAULT 'carta'");
    }
};
