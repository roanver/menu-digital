<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('font', 50)->default('Inter')->after('primary_color');
            $table->string('bg_color', 7)->default('#f9fafb')->after('primary_color');
            $table->tinyInteger('show_price')->default(1)->after('primary_color');
            $table->tinyInteger('show_description')->default(1)->after('primary_color');
            $table->string('welcome_message', 255)->nullable()->after('primary_color');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['font', 'bg_color', 'show_price', 'show_description', 'welcome_message']);
        });
    }
};
