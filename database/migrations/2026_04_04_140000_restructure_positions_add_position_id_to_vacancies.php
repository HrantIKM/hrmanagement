<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->decimal('min_salary', 12, 2)->nullable()->after('title');
            $table->decimal('max_salary', 12, 2)->nullable()->after('min_salary');
            $table->string('grade_level', 64)->nullable()->after('max_salary');
        });

        Schema::table('vacancies', function (Blueprint $table) {
            $table->foreignId('position_id')->nullable()->after('name')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn(['min_salary', 'max_salary', 'grade_level']);
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
        });
    }
};
