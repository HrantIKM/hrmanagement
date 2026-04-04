<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
        });

        Schema::table('goals', function (Blueprint $table) {
            $table->decimal('target_value', 14, 2)->nullable()->after('title');
            $table->decimal('current_value', 14, 2)->nullable()->after('target_value');
            $table->date('deadline')->nullable()->after('current_value');
            $table->string('type', 32)->default('quantitative')->after('deadline');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('reviewer_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->decimal('rating', 3, 2)->after('reviewer_id');
            $table->text('feedback_text')->nullable()->after('rating');
            $table->string('review_period', 8)->after('feedback_text');
        });

        Schema::table('salaries', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('salaries', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->decimal('amount', 14, 2)->after('user_id');
            $table->date('effective_date')->after('amount');
            $table->string('change_reason', 32)->after('effective_date');
        });

        Schema::table('payslips', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('payslips', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('period_month')->after('user_id');
            $table->unsignedSmallInteger('period_year')->after('period_month');
            $table->decimal('base_amount', 14, 2)->after('period_year');
            $table->decimal('bonus', 14, 2)->default(0)->after('base_amount');
            $table->decimal('deductions', 14, 2)->default(0)->after('bonus');
            $table->decimal('net_total', 14, 2)->after('deductions');
            $table->string('pdf_path')->nullable()->after('net_total');
        });
    }

    public function down(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'period_month',
                'period_year',
                'base_amount',
                'bonus',
                'deductions',
                'net_total',
                'pdf_path',
            ]);
            $table->string('name');
        });

        Schema::table('salaries', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'amount', 'effective_date', 'change_reason']);
            $table->string('name');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['reviewer_id']);
            $table->dropColumn(['user_id', 'reviewer_id', 'rating', 'feedback_text', 'review_period']);
            $table->string('name');
        });

        Schema::table('goals', function (Blueprint $table) {
            $table->dropColumn(['target_value', 'current_value', 'deadline', 'type']);
        });

        Schema::table('goals', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
        });
    }
};
