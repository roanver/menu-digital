<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('amount');
            $table->date('paid_at');
            $table->enum('method', ['transferencia', 'efectivo', 'otro'])->default('transferencia');
            $table->tinyInteger('months')->unsigned()->default(1);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
