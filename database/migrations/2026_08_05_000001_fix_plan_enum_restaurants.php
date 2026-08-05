<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: expand enum to include both old and new values
        DB::statement("ALTER TABLE restaurants MODIFY COLUMN plan ENUM('trial','basic','pro','expired','carta','pedidos','full') NOT NULL DEFAULT 'carta'");

        // Step 2: migrate existing data
        DB::statement("UPDATE restaurants SET plan = 'carta' WHERE plan IN ('trial','basic','expired')");
        DB::statement("UPDATE restaurants SET plan = 'full' WHERE plan = 'pro'");

        // Step 3: restrict to new values only
        DB::statement("ALTER TABLE restaurants MODIFY COLUMN plan ENUM('carta','pedidos','full') NOT NULL DEFAULT 'carta'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE restaurants MODIFY COLUMN plan ENUM('carta','pedidos','full','trial','basic','pro','expired') NOT NULL DEFAULT 'trial'");
        DB::statement("UPDATE restaurants SET plan = 'trial' WHERE plan IN ('carta','pedidos')");
        DB::statement("UPDATE restaurants SET plan = 'pro' WHERE plan = 'full'");
        DB::statement("ALTER TABLE restaurants MODIFY COLUMN plan ENUM('trial','basic','pro','expired') NOT NULL DEFAULT 'trial'");
    }
};
