<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->unique('name');
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('name')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->dropColumn('description');
        });
    }
};
