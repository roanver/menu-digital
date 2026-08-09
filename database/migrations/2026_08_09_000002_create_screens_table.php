<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('token', 40)->unique();
            $table->json('category_ids')->nullable();
            $table->unsignedTinyInteger('columns')->default(2);
            $table->enum('orientation', ['landscape', 'portrait'])->default('landscape');
            $table->boolean('show_images')->default(false);
            $table->boolean('show_promos_rotation')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screens');
    }
};
