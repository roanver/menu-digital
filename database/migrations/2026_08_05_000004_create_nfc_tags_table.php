<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nfc_tags', function (Blueprint $table) {
            $table->id();
            $table->string('code', 8)->unique();
            $table->foreignId('restaurant_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['review', 'menu']);
            $table->string('label')->nullable();
            $table->string('target_url')->nullable();
            $table->unsignedBigInteger('scans_count')->default(0);
            $table->timestamp('last_scanned_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nfc_tags');
    }
};
