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
        // ── 1. Agregar qr_tag_id (nullable por ahora, se rellena antes de poner NOT NULL) ──
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->unsignedBigInteger('qr_tag_id')->nullable()->after('nfc_tag_id');
            $table->foreign('qr_tag_id', 'rt_qr_tag_fk')
                  ->references('id')->on('nfc_tags')->cascadeOnDelete();
        });

        // ── 2. Migración de datos ──────────────────────────────────────────────────────────

        // Mesas con tag virtual (is_physical=0): ese tag pasa a ser el QR tag.
        // nfc_tag_id queda null (se hace nullable más abajo).
        DB::statement("
            UPDATE restaurant_tables rt
            INNER JOIN nfc_tags nt ON rt.nfc_tag_id = nt.id
            SET rt.qr_tag_id  = rt.nfc_tag_id,
                rt.nfc_tag_id = NULL
            WHERE nt.is_physical = 0
        ");

        // Mesas con chip físico (is_physical=1): crear un nuevo tag virtual para el QR.
        // El nfc_tag_id apunta al chip físico y se conserva.
        $physicalLinked = DB::table('restaurant_tables as rt')
            ->join('nfc_tags as nt', 'rt.nfc_tag_id', '=', 'nt.id')
            ->where('nt.is_physical', true)
            ->whereNotNull('rt.nfc_tag_id')
            ->select('rt.id as table_id', 'rt.name', 'rt.restaurant_id', 'rt.is_active')
            ->get();

        foreach ($physicalLinked as $row) {
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
                ->where('id', $row->table_id)
                ->update(['qr_tag_id' => $tagId]);
        }

        // ── 3. qr_tag_id NOT NULL (todos los registros ya están poblados) ────────────────
        DB::statement('ALTER TABLE restaurant_tables MODIFY qr_tag_id BIGINT UNSIGNED NOT NULL');

        // Unique en qr_tag_id
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->unique('qr_tag_id', 'rt_qr_tag_unique');
        });

        // ── 4. nfc_tag_id → nullable, ON DELETE SET NULL (antes era NOT NULL + CASCADE) ──
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->dropForeign('restaurant_tables_nfc_tag_id_foreign');
            $table->dropUnique('restaurant_tables_nfc_tag_id_unique');
        });

        DB::statement('ALTER TABLE restaurant_tables MODIFY nfc_tag_id BIGINT UNSIGNED NULL');

        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->unique('nfc_tag_id', 'rt_nfc_tag_unique');
            $table->foreign('nfc_tag_id', 'rt_nfc_tag_fk')
                  ->references('id')->on('nfc_tags')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->dropForeign('rt_qr_tag_fk');
            $table->dropUnique('rt_qr_tag_unique');
            $table->dropForeign('rt_nfc_tag_fk');
            $table->dropUnique('rt_nfc_tag_unique');
        });

        // Restaurar nfc_tag_id a NOT NULL + CASCADE (best-effort: apuntar al qr_tag_id)
        DB::statement("UPDATE restaurant_tables SET nfc_tag_id = qr_tag_id WHERE nfc_tag_id IS NULL");
        DB::statement('ALTER TABLE restaurant_tables MODIFY nfc_tag_id BIGINT UNSIGNED NOT NULL');

        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->dropColumn('qr_tag_id');
            $table->unique('nfc_tag_id');
            $table->foreign('nfc_tag_id')->references('id')->on('nfc_tags')->cascadeOnDelete();
        });
    }
};
