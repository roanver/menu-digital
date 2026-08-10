<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Agregar nfc_chip_written ──────────────────────────────────────
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->boolean('nfc_chip_written')->default(false)->after('is_active');
        });

        // ── 2. Backfill: crear NFC virtual para mesas que no tienen nfc_tag_id ─
        $rows = DB::table('restaurant_tables')->whereNull('nfc_tag_id')->get();

        foreach ($rows as $row) {
            do {
                $code = strtoupper(Str::random(8));
            } while (DB::table('nfc_tags')->where('code', $code)->exists());

            $tagId = DB::table('nfc_tags')->insertGetId([
                'code'            => $code,
                'type'            => 'menu',
                'restaurant_id'   => $row->restaurant_id,
                'label'           => $row->name,
                'is_active'       => $row->is_active,
                'is_physical'     => false,
                'scans_count'     => 0,
                'last_scanned_at' => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            DB::table('restaurant_tables')
                ->where('id', $row->id)
                ->update(['nfc_tag_id' => $tagId]);
        }

        // ── 3. nfc_tag_id → NOT NULL + cascadeOnDelete ───────────────────────
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->dropForeign('rt_nfc_tag_fk');
            $table->dropUnique('rt_nfc_tag_unique');
        });

        DB::statement('ALTER TABLE restaurant_tables MODIFY nfc_tag_id BIGINT UNSIGNED NOT NULL');

        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->unique('nfc_tag_id', 'rt_nfc_tag_unique');
            $table->foreign('nfc_tag_id', 'rt_nfc_tag_fk')
                  ->references('id')->on('nfc_tags')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->dropColumn('nfc_chip_written');
        });

        // Restaurar nullable + nullOnDelete (best-effort)
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->dropForeign('rt_nfc_tag_fk');
            $table->dropUnique('rt_nfc_tag_unique');
        });

        DB::statement('ALTER TABLE restaurant_tables MODIFY nfc_tag_id BIGINT UNSIGNED NULL');

        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->unique('nfc_tag_id', 'rt_nfc_tag_unique');
            $table->foreign('nfc_tag_id', 'rt_nfc_tag_fk')
                  ->references('id')->on('nfc_tags')->nullOnDelete();
        });
    }
};
