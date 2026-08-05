<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->boolean('accepts_orders')->default(false)->after('welcome_message');
            $table->boolean('accepts_delivery')->default(false)->after('accepts_orders');
            $table->text('delivery_zone')->nullable()->after('accepts_delivery');
            $table->unsignedInteger('min_order')->nullable()->after('delivery_zone');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['accepts_orders', 'accepts_delivery', 'delivery_zone', 'min_order']);
        });
    }
};
