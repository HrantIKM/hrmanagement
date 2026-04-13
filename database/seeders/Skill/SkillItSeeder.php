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

        /** Top 15 languages + frameworks, libraries, and tools (exactly 100 entries). */
        $skills = [
            // —— Programming languages (15) ——
            ['name' => 'JavaScript', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'TypeScript', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Python', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Java', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'C#', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Go', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Rust', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'PHP', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Kotlin', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Swift', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Ruby', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'C++', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'SQL', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Scala', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Dart', 'category' => SkillCategory::TECHNICAL],

            // —— JavaScript / TypeScript front & back ——
            ['name' => 'React', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Vue.js', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Angular', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Svelte', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Next.js', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Nuxt.js', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Node.js', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Express.js', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'NestJS', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Webpack', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Vite', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Jest', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Cypress', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Playwright', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Redux', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Prisma', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Tailwind CSS', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'HTML & CSS', 'category' => SkillCategory::TECHNICAL],

            // —— Python ——
            ['name' => 'Django', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'FastAPI', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Flask', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Pandas', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'NumPy', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'scikit-learn', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'PyTorch', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'TensorFlow', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'pytest', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Celery', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'SQLAlchemy', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Apache Airflow', 'category' => SkillCategory::TECHNICAL],

            // —— Java / JVM ——
            ['name' => 'Spring Boot', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Hibernate & JPA', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Maven', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Gradle', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'JUnit', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Mockito', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Quarkus', 'category' => SkillCategory::TECHNICAL],

            // —— C# / .NET ——
            ['name' => 'ASP.NET Core', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Entity Framework Core', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Blazor', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'xUnit', 'category' => SkillCategory::TECHNICAL],

            // —— Go ——
            ['name' => 'Gin', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Echo', 'category' => SkillCategory::TECHNICAL],

            // —— Rust ——
            ['name' => 'Tokio', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Actix Web', 'category' => SkillCategory::TECHNICAL],

            // —— PHP ——
            ['name' => 'Laravel', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Symfony', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'PHPUnit', 'category' => SkillCategory::TECHNICAL],

            // —— Kotlin / Android ——
            ['name' => 'Ktor', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Android SDK', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Jetpack Compose', 'category' => SkillCategory::TECHNICAL],

            // —— Swift / Apple ——
            ['name' => 'SwiftUI', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'UIKit', 'category' => SkillCategory::TECHNICAL],

            // —— Ruby ——
            ['name' => 'Ruby on Rails', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'RSpec', 'category' => SkillCategory::TECHNICAL],

            // —— C++ ——
            ['name' => 'CMake', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Qt', 'category' => SkillCategory::TECHNICAL],

            // —— Scala / data ——
            ['name' => 'Akka', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Apache Spark', 'category' => SkillCategory::TECHNICAL],

            // —— Dart / mobile ——
            ['name' => 'Flutter', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'React Native', 'category' => SkillCategory::TECHNICAL],

            // —— Datastores & messaging ——
            ['name' => 'PostgreSQL', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'MySQL', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'MongoDB', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Redis', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Elasticsearch', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Apache Kafka', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'RabbitMQ', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'GraphQL', 'category' => SkillCategory::TECHNICAL],

            // —— APIs & architecture ——
            ['name' => 'REST API design', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Microservices', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'OAuth 2.0 & OpenID Connect', 'category' => SkillCategory::TECHNICAL],

            // —— DevOps, cloud, observability ——
            ['name' => 'Docker', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Kubernetes', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'AWS', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Microsoft Azure', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Google Cloud Platform', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Terraform', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Ansible', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Jenkins', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'GitHub Actions', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Prometheus & Grafana', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Nginx', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Linux administration', 'category' => SkillCategory::TECHNICAL],
            ['name' => 'Git', 'category' => SkillCategory::TECHNICAL],
        ];

        if (count($skills) !== 100) {
            throw new \RuntimeException('SkillItSeeder must define exactly 100 skills; got '.count($skills).'.');
        }

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
