<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('name')->constrained()->nullOnDelete();
        });

        Schema::table('timesheets', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('name')->constrained()->nullOnDelete();
        });

        Schema::table('goals', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('name')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('timesheets', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('goals', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
