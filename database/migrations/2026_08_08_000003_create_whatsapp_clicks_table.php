<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('type', ['retiro', 'delivery', 'unknown'])->default('unknown');
            $table->unsignedInteger('count')->default(0);
            $table->timestamps();

            $table->unique(['restaurant_id', 'date', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_clicks');
    }
};
