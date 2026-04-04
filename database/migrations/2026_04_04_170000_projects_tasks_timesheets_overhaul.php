<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->date('start_date')->nullable()->after('description');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('status', 32)->default('planning')->after('end_date');
        });

        Schema::create('project_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['project_id', 'user_id']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
            $table->string('priority', 32)->default('low')->after('description');
            $table->string('status', 32)->default('todo')->after('priority');
            $table->date('due_date')->nullable()->after('status');
            $table->foreignId('project_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });

        Schema::table('timesheets', function (Blueprint $table) {
            $table->foreignId('task_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->date('date')->nullable()->after('task_id');
            $table->time('start_time')->nullable()->after('date');
            $table->time('end_time')->nullable()->after('start_time');
            $table->unsignedInteger('duration_minutes')->nullable()->after('end_time');
            $table->text('note')->nullable()->after('duration_minutes');
        });

        Schema::table('timesheets', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('timesheets', function (Blueprint $table) {
            $table->string('name')->after('id');
        });

        Schema::table('timesheets', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->dropColumn(['task_id', 'date', 'start_time', 'end_time', 'duration_minutes', 'note']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn(['project_id', 'description', 'priority', 'status', 'due_date']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
        });

        Schema::dropIfExists('project_user');

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['description', 'start_date', 'end_date', 'status']);
        });
    }
};
