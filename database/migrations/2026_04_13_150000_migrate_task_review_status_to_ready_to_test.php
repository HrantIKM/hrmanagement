<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tasks')
            ->where('status', 'review')
            ->update(['status' => 'ready_to_test']);
    }

    public function down(): void
    {
        DB::table('tasks')
            ->where('status', 'ready_to_test')
            ->update(['status' => 'review']);
    }
};
