<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nfc_tags', function (Blueprint $table) {
            $table->foreignId('kit_id')->nullable()->after('is_physical')->constrained('kits')->nullOnDelete();
            $table->string('slot_label')->nullable()->after('kit_id');
        });
    }

    public function down(): void
    {
        Schema::table('nfc_tags', function (Blueprint $table) {
            $table->dropForeign(['kit_id']);
            $table->dropColumn(['kit_id', 'slot_label']);
        });
    }
};
