<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('restaurant_user')) {
            Schema::create('restaurant_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->enum('role', ['owner', 'staff'])->default('owner');
                $table->timestamps();

                $table->unique(['restaurant_id', 'user_id']);
            });
        }

        // Migrar datos existentes desde users.restaurant_id + users.role
        DB::table('users')
            ->whereNotNull('restaurant_id')
            ->whereIn('role', ['owner', 'staff'])
            ->orderBy('id')
            ->each(function ($user) {
                DB::table('restaurant_user')->insertOrIgnore([
                    'restaurant_id' => $user->restaurant_id,
                    'user_id'       => $user->id,
                    'role'          => $user->role,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_user');
    }
};
