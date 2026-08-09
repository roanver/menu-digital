<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->json('suggestions');
            $table->unsignedSmallInteger('score')->nullable()->comment('0-100 overall menu quality score');
            $table->timestamps();

            $table->index('restaurant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_audits');
    }
};
