<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('screens', function (Blueprint $table) {
            $table->char('theme', 20)->default('dark')->after('is_active');
            $table->char('accent_color', 7)->nullable()->after('theme');
            $table->string('bg_image', 255)->nullable()->after('accent_color');
        });
    }

    public function down(): void
    {
        Schema::table('screens', function (Blueprint $table) {
            $table->dropColumn(['theme', 'accent_color', 'bg_image']);
        });
    }
};
