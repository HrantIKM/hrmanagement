<?php

namespace Database\Seeders\Skill;

use App\Models\Department\Department;
use App\Models\Department\Enums\DepartmentCode;
use App\Models\Skill\Enums\SkillCategory;
use App\Models\Skill\Skill;
use Illuminate\Database\Seeder;

class SkillItSeeder extends Seeder
{
    public function run(): void
    {
        $department = Department::query()->where('name', DepartmentCode::IT->value)->first();
        if (!$department) {
            $this->command?->warn('SkillItSeeder skipped: department IT not found. Run DepartmentSeeder first.');

            return;
        }

        $skills = [
            ['name' => 'Python', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Java', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'JavaScript', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'TypeScript', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'C# / .NET', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Go', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Rust', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'PHP / Laravel', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Ruby on Rails', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Swift', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Kotlin', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'SQL', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'PostgreSQL', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'MySQL / MariaDB', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'MongoDB', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Redis', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Docker', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Kubernetes', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'AWS', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Microsoft Azure', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Google Cloud Platform', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Terraform', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Ansible', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'CI/CD (Jenkins / GitHub Actions)', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Git / code review', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Linux administration', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Bash / shell scripting', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'REST API design', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'GraphQL', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Microservices architecture', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'OAuth / OIDC', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Application security (OWASP)', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'System design', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'TCP/IP & networking', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Observability (metrics, logs, traces)', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Apache Kafka', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'RabbitMQ / message queues', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Elasticsearch / OpenSearch', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Agile / Scrum (delivery)', 'category' => SkillCategory::SOFT],
            ['name' => 'Technical writing', 'category' => SkillCategory::SOFT],
            ['name' => 'English for engineering communication', 'category' => SkillCategory::LANGUAGE],
            ['name' => 'German for engineering communication', 'category' => SkillCategory::LANGUAGE],
            ['name' => 'Incident response & on-call', 'category' => SkillCategory::SOFT],
            ['name' => 'Performance tuning & profiling', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Test automation (unit / integration / e2e)', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Infrastructure as code patterns', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Data modeling', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'API documentation (OpenAPI)', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Pair programming & mentoring', 'category' => SkillCategory::SOFT],
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
