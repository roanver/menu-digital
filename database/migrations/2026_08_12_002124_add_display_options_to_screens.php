<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('screens', function (Blueprint $table) {
            $table->string('density', 15)->default('comfortable');
            $table->boolean('show_descriptions')->default(false);
            $table->boolean('show_out_of_stock')->default(true);
            $table->string('out_of_stock_style', 12)->default('dimmed');
            $table->boolean('show_logo')->default(true);
            $table->unsignedTinyInteger('page_seconds')->default(15);
            $table->string('footer_message', 200)->nullable();
            $table->boolean('show_qr')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('screens', function (Blueprint $table) {
            $table->dropColumn([
                'density', 'show_descriptions', 'show_out_of_stock',
                'out_of_stock_style', 'show_logo', 'page_seconds',
                'footer_message', 'show_qr',
            ]);
        });
    }
};
