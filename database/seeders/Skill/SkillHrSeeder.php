<?php

namespace Database\Seeders\Skill;

use App\Models\Department\Department;
use App\Models\Department\Enums\DepartmentCode;
use App\Models\Skill\Enums\SkillCategory;
use App\Models\Skill\Skill;
use Illuminate\Database\Seeder;

class SkillHrSeeder extends Seeder
{
    public function run(): void
    {
        $department = Department::query()->where('name', DepartmentCode::HR->value)->first();
        if (!$department) {
            $this->command?->warn('SkillHrSeeder skipped: department HR not found. Run DepartmentSeeder first.');

            return;
        }

        $skills = [
            ['name' => 'Talent acquisition strategy', 'category' => SkillCategory::SOFT],
            ['name' => 'Sourcing & pipelining', 'category' => SkillCategory::SOFT],
            ['name' => 'Structured interviewing', 'category' => SkillCategory::SOFT],
            ['name' => 'Employer branding', 'category' => SkillCategory::SOFT],
            ['name' => 'Onboarding program design', 'category' => SkillCategory::SOFT],
            ['name' => 'Offboarding & knowledge transfer', 'category' => SkillCategory::SOFT],
            ['name' => 'Payroll processing', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Benefits administration', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Labor law & compliance', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'HR policy authoring', 'category' => SkillCategory::SOFT],
            ['name' => 'HRIS administration', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Workday configuration', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Performance management cycles', 'category' => SkillCategory::SOFT],
            ['name' => '360 feedback facilitation', 'category' => SkillCategory::SOFT],
            ['name' => 'Succession planning', 'category' => SkillCategory::SOFT],
            ['name' => 'Training needs analysis', 'category' => SkillCategory::SOFT],
            ['name' => 'LMS administration', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Compensation benchmarking', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Job evaluation & leveling', 'category' => SkillCategory::SOFT],
            ['name' => 'DEI program design', 'category' => SkillCategory::SOFT],
            ['name' => 'Employee relations', 'category' => SkillCategory::SOFT],
            ['name' => 'Grievance & disciplinary processes', 'category' => SkillCategory::SOFT],
            ['name' => 'HR analytics & dashboards', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'People metrics (turnover, time-to-hire)', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'OKRs for people teams', 'category' => SkillCategory::SOFT],
            ['name' => 'Workforce planning', 'category' => SkillCategory::SOFT],
            ['name' => 'Organization design', 'category' => SkillCategory::SOFT],
            ['name' => 'Change management (HR side)', 'category' => SkillCategory::SOFT],
            ['name' => 'Coaching managers', 'category' => SkillCategory::SOFT],
            ['name' => 'Workplace mediation', 'category' => SkillCategory::SOFT],
            ['name' => 'HR business partnering', 'category' => SkillCategory::SOFT],
            ['name' => 'Culture & engagement programs', 'category' => SkillCategory::SOFT],
            ['name' => 'Engagement survey design', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Contract & template management', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Work authorization & mobility basics', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'HR audit preparation', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Occupational health coordination', 'category' => SkillCategory::SOFT],
            ['name' => 'Workplace safety compliance', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'HR reporting to leadership', 'category' => SkillCategory::SOFT],
            ['name' => 'Stakeholder communication', 'category' => SkillCategory::SOFT],
            ['name' => 'Internal communications (HR)', 'category' => SkillCategory::SOFT],
            ['name' => 'English for HR correspondence', 'category' => SkillCategory::LANGUAGE],
            ['name' => 'German for HR correspondence', 'category' => SkillCategory::LANGUAGE],
            ['name' => 'Data privacy in HR (GDPR context)', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Learning content curation', 'category' => SkillCategory::SOFT],
            ['name' => 'Compensation communication', 'category' => SkillCategory::SOFT],
            ['name' => 'HR project management', 'category' => SkillCategory::SOFT],
            ['name' => 'Exit interview analysis', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Employer value proposition messaging', 'category' => SkillCategory::SOFT],
        ];

        foreach ($skills as $row) {
            Skill::query()->updateOrCreate(
                [
                    'department_id' => $department->id,
                    'name' => $row['name'],
                ],
                ['category' => $row['category']]
            );
        }
    }
}
