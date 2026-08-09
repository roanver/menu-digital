<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->unsignedSmallInteger('ai_imports_this_month')->default(0)->after('plan');
            $table->timestamp('ai_imports_reset_at')->nullable()->after('ai_imports_this_month');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['ai_imports_this_month', 'ai_imports_reset_at']);
        });
    }
};
