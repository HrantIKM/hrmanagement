<?php

use App\Models\User\Enums\EmploymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('email_reminder')->constrained()->nullOnDelete();
            $table->foreignId('position_id')->nullable()->after('department_id')->constrained()->nullOnDelete();
            $table->decimal('salary', 12, 2)->nullable()->after('position_id');
            $table->date('hire_date')->nullable()->after('salary');
            $table->string('employment_status', 32)->default(EmploymentStatus::ACTIVE)->after('hire_date');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['position_id']);
            $table->dropColumn([
                'department_id',
                'position_id',
                'salary',
                'hire_date',
                'employment_status',
            ]);
        });
    }
};
