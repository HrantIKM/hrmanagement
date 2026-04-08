<?php

namespace Database\Seeders\Skill;

use App\Models\Department\Department;
use App\Models\Department\Enums\DepartmentCode;
use App\Models\Skill\Enums\SkillCategory;
use App\Models\Skill\Skill;
use Illuminate\Database\Seeder;

class SkillSalesSeeder extends Seeder
{
    public function run(): void
    {
        $department = Department::query()->where('name', DepartmentCode::SALES->value)->first();
        if (!$department) {
            $this->command?->warn('SkillSalesSeeder skipped: department Sales not found. Run DepartmentSeeder first.');

            return;
        }

        $skills = [
            ['name' => 'Prospecting & lead research', 'category' => SkillCategory::SOFT],
            ['name' => 'Cold calling', 'category' => SkillCategory::SOFT],
            ['name' => 'Cold email outreach', 'category' => SkillCategory::SOFT],
            ['name' => 'Social selling', 'category' => SkillCategory::SOFT],
            ['name' => 'LinkedIn Sales Navigator', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'SPIN selling', 'category' => SkillCategory::SOFT],
            ['name' => 'Consultative discovery', 'category' => SkillCategory::SOFT],
            ['name' => 'Objection handling', 'category' => SkillCategory::SOFT],
            ['name' => 'Closing techniques', 'category' => SkillCategory::SOFT],
            ['name' => 'Pipeline management', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Forecasting accuracy', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Account planning', 'category' => SkillCategory::SOFT],
            ['name' => 'Territory management', 'category' => SkillCategory::SOFT],
            ['name' => 'Key account management', 'category' => SkillCategory::SOFT],
            ['name' => 'Channel & partner sales', 'category' => SkillCategory::SOFT],
            ['name' => 'Partner enablement', 'category' => SkillCategory::SOFT],
            ['name' => 'CRM hygiene & data quality', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Salesforce core usage', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'HubSpot sales workflows', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'CPQ basics', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Commercial negotiation', 'category' => SkillCategory::SOFT],
            ['name' => 'Pricing & discount strategy', 'category' => SkillCategory::SOFT],
            ['name' => 'RFP / RFQ response', 'category' => SkillCategory::SOFT],
            ['name' => 'Product demonstration', 'category' => SkillCategory::SOFT],
            ['name' => 'Value proposition design', 'category' => SkillCategory::SOFT],
            ['name' => 'Storytelling for sales', 'category' => SkillCategory::SOFT],
            ['name' => 'Networking & events', 'category' => SkillCategory::SOFT],
            ['name' => 'Trade show execution', 'category' => SkillCategory::SOFT],
            ['name' => 'Market & competitor research', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Ideal customer profile definition', 'category' => SkillCategory::SOFT],
            ['name' => 'Lead qualification (BANT / MEDDIC)', 'category' => SkillCategory::SOFT],
            ['name' => 'MEDDIC qualification', 'category' => SkillCategory::SOFT],
            ['name' => 'Challenger sale methodology', 'category' => SkillCategory::SOFT],
            ['name' => 'Customer success handoff', 'category' => SkillCategory::SOFT],
            ['name' => 'Renewal management', 'category' => SkillCategory::SOFT],
            ['name' => 'Upsell & cross-sell', 'category' => SkillCategory::SOFT],
            ['name' => 'Competitive battlecards', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Sales enablement collaboration', 'category' => SkillCategory::SOFT],
            ['name' => 'Collateral & pitch deck usage', 'category' => SkillCategory::SOFT],
            ['name' => 'Win / loss analysis', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'English for client calls', 'category' => SkillCategory::LANGUAGE],
            ['name' => 'German for client calls', 'category' => SkillCategory::LANGUAGE],
            ['name' => 'Proposal writing', 'category' => SkillCategory::SOFT],
            ['name' => 'Multi-threading enterprise deals', 'category' => SkillCategory::SOFT],
            ['name' => 'Mutual action plans', 'category' => SkillCategory::SOFT],
            ['name' => 'Sales quota planning', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Commission structure literacy', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Legal review coordination (sales)', 'category' => SkillCategory::SOFT],
            ['name' => 'Video selling presence', 'category' => SkillCategory::SOFT],
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
