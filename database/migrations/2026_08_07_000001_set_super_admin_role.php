<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (config('app.super_admin_email')) {
            DB::table('users')
                ->where('email', config('app.super_admin_email'))
                ->update(['role' => 'super_admin']);
        }
    }

    public function down(): void
    {
        if (config('app.super_admin_email')) {
            DB::table('users')
                ->where('email', config('app.super_admin_email'))
                ->where('role', 'super_admin')
                ->update(['role' => 'owner']);
        }
    }
};
