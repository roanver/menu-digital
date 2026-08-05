<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nfc_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nfc_tag_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->unsignedBigInteger('count')->default(0);
            $table->unique(['nfc_tag_id', 'date']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nfc_scans');
    }
};
