<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->string('category', 32)->default('technical')->after('name');
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->unique('name');
        });

        Schema::create('candidate_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['candidate_id', 'skill_id']);
        });

        Schema::create('skill_vacancy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vacancy_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['skill_id', 'vacancy_id']);
        });

        Schema::table('vacancies', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
        });

        Schema::table('vacancies', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
            $table->string('status', 32)->default('open')->after('description');
            $table->date('closing_date')->nullable()->after('status');
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->renameColumn('name', 'full_name');
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->string('email')->nullable()->after('full_name');
            $table->string('resume_path', 512)->nullable()->after('email');
            $table->json('raw_ai_data')->nullable()->after('resume_path');
            $table->unsignedInteger('match_score')->nullable()->after('raw_ai_data');
            $table->foreignId('vacancy_id')->nullable()->after('match_score')->constrained()->nullOnDelete();
        });

        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['vacancy_id', 'candidate_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');

        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign(['vacancy_id']);
            $table->dropColumn(['vacancy_id', 'match_score', 'raw_ai_data', 'resume_path', 'email']);
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->renameColumn('full_name', 'name');
        });

        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropColumn(['closing_date', 'status', 'description']);
        });

        Schema::table('vacancies', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
        });

        Schema::dropIfExists('skill_vacancy');
        Schema::dropIfExists('candidate_skill');

        Schema::table('skills', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
