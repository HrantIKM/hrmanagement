<?php

namespace Database\Seeders\User;

use App\Models\Department\Department;
use App\Models\Department\Enums\DepartmentCode;
use App\Models\Goal\Enums\GoalType;
use App\Models\Goal\Goal;
use App\Models\Payslip\Payslip;
use App\Models\Position\Position;
use App\Models\Project\Project;
use App\Models\Review\Enums\ReviewPeriod;
use App\Models\Review\Review;
use App\Models\RoleAndPermission\Enums\RoleType;
use App\Models\Salary\Enums\SalaryChangeReason;
use App\Models\Salary\Salary;
use App\Models\Skill\Skill;
use App\Models\User\Enums\EmploymentStatus;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    private const ADMIN_EMAIL = 'admin@admin.com';

    /**
     * @return list<array{first_name: string, last_name: string}>
     */
    private function armenianProfiles(): array
    {
        return [
            ['first_name' => 'Armen', 'last_name' => 'Petrosyan'],
            ['first_name' => 'Ani', 'last_name' => 'Sargsyan'],
            ['first_name' => 'Gevorg', 'last_name' => 'Khachatryan'],
            ['first_name' => 'Nare', 'last_name' => 'Harutyunyan'],
            ['first_name' => 'Hayk', 'last_name' => 'Grigoryan'],
            ['first_name' => 'Mariam', 'last_name' => 'Manukyan'],
            ['first_name' => 'Tigran', 'last_name' => 'Avetisyan'],
            ['first_name' => 'Lilit', 'last_name' => 'Vardanyan'],
            ['first_name' => 'Vardan', 'last_name' => 'Martirosyan'],
            ['first_name' => 'Gayane', 'last_name' => 'Hovhannisyan'],
            ['first_name' => 'Karen', 'last_name' => 'Mkrtchyan'],
            ['first_name' => 'Astghik', 'last_name' => 'Poghosyan'],
        ];
    }

    /**
     * @return list<string>
     */
    private function timezones(): array
    {
        return [
            'Asia/Yerevan',
            'Europe/Berlin',
            'UTC',
            'Europe/London',
            'Asia/Dubai',
        ];
    }

    public function run(): void
    {
        $departments = Department::query()
            ->whereIn('name', DepartmentCode::values())
            ->get()
            ->keyBy('name');

        if ($departments->isEmpty()) {
            $this->command?->warn('UserSeeder skipped: no departments found. Run DepartmentSeeder first.');

            return;
        }

        $passwordHash = Hash::make('password');
        $domain = 'seed.local';
        $profiles = $this->armenianProfiles();
        $tzList = $this->timezones();

        /** @var Collection<int, User> $seededUsers */
        $seededUsers = collect();

        DB::transaction(function () use ($passwordHash, $domain, $profiles, $tzList, $departments, &$seededUsers) {
            foreach ($profiles as $index => $profile) {
                $deptName = match ($index % 3) {
                    0 => DepartmentCode::IT->value,
                    1 => DepartmentCode::HR->value,
                    default => DepartmentCode::SALES->value,
                };

                $department = $departments->get($deptName) ?? $departments->first();

                $position = Position::query()
                    ->where('department_id', $department->id)
                    ->inRandomOrder()
                    ->first();

                $hireDate = now()->subYears(random_int(0, 7))->subDays(random_int(0, 320));
                $employmentStatus = match (random_int(0, 12)) {
                    11 => EmploymentStatus::ON_LEAVE,
                    12 => EmploymentStatus::TERMINATED,
                    default => EmploymentStatus::ACTIVE,
                };

                $salary = (float) random_int(350_000, 1_200_000);

                $emailLocal = Str::lower($profile['first_name'] . '.' . $profile['last_name']);
                $email = $emailLocal . '@' . $domain;
                if (User::query()->where('email', $email)->exists()) {
                    $email = $emailLocal . '.' . $index . '@' . $domain;
                }

                $user = User::query()->create([
                    'first_name' => $profile['first_name'],
                    'last_name' => $profile['last_name'],
                    'email' => $email,
                    'email_verified_at' => now(),
                    'password' => $passwordHash,
                    'remember_token' => Str::random(10),
                    'timezone' => $tzList[$index % count($tzList)],
                    'email_notification' => (bool) random_int(0, 1),
                    'email_reminder' => (bool) random_int(0, 1),
                    'department_id' => $department->id,
                    'position_id' => $position?->id,
                    'salary' => $salary,
                    'hire_date' => $hireDate,
                    'employment_status' => $employmentStatus,
                ]);

                $user->assignRole(RoleType::USER);

                $skillIds = Skill::query()
                    ->where('department_id', $department->id)
                    ->inRandomOrder()
                    ->limit(random_int(5, 14))
                    ->pluck('id')
                    ->all();

                if ($skillIds !== []) {
                    $user->skills()->sync($skillIds);
                }

                $seededUsers->push($user);
            }

            $admin = User::query()->where('email', self::ADMIN_EMAIL)->first();

            $this->attachProjectsIfAny($seededUsers);
            $this->seedGoals($seededUsers);
            $this->seedSalaryHistory($seededUsers);
            $this->seedPayslips($seededUsers);
            $this->seedReviews($seededUsers, $admin);
        });
    }

    /**
     * @param  Collection<int, User>  $users
     */
    private function attachProjectsIfAny(Collection $users): void
    {
        $projectIds = Project::query()->pluck('id');
        if ($projectIds->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            $take = min(random_int(1, 3), $projectIds->count());
            $ids = $projectIds->shuffle()->take($take)->values()->all();
            $user->projects()->syncWithoutDetaching($ids);
        }
    }

    /**
     * @param  Collection<int, User>  $users
     */
    private function seedGoals(Collection $users): void
    {
        $goalTemplates = [
            ['title' => 'Ship roadmap milestones on time', 'type' => GoalType::QUANTITATIVE, 'target' => 100, 'current' => 68],
            ['title' => 'Improve cross-team collaboration', 'type' => GoalType::QUALITATIVE, 'target' => null, 'current' => null],
            ['title' => 'Reduce incident response time', 'type' => GoalType::QUANTITATIVE, 'target' => 40, 'current' => 22],
            ['title' => 'Complete compliance training track', 'type' => GoalType::QUANTITATIVE, 'target' => 10, 'current' => 7],
        ];

        foreach ($users as $i => $user) {
            foreach ([0, 1] as $offset) {
                $tpl = $goalTemplates[($i + $offset) % count($goalTemplates)];
                Goal::query()->create([
                    'user_id' => $user->id,
                    'title' => $tpl['title'],
                    'target_value' => $tpl['target'],
                    'current_value' => $tpl['current'],
                    'deadline' => now()->addMonths(random_int(2, 8))->startOfMonth(),
                    'type' => $tpl['type'],
                ]);
            }
        }
    }

    /**
     * @param  Collection<int, User>  $users
     */
    private function seedSalaryHistory(Collection $users): void
    {
        foreach ($users as $user) {
            $current = (float) $user->salary;

            Salary::query()->create([
                'user_id' => $user->id,
                'amount' => round($current * 0.88, 2),
                'effective_date' => $user->hire_date ?? now()->subYears(2),
                'change_reason' => SalaryChangeReason::ANNUAL,
            ]);

            Salary::query()->create([
                'user_id' => $user->id,
                'amount' => round($current * 0.95, 2),
                'effective_date' => now()->subMonths(14)->startOfMonth(),
                'change_reason' => SalaryChangeReason::ADJUSTMENT,
            ]);

            Salary::query()->create([
                'user_id' => $user->id,
                'amount' => $current,
                'effective_date' => now()->subMonths(3)->startOfMonth(),
                'change_reason' => SalaryChangeReason::PROMOTION,
            ]);
        }
    }

    /**
     * @param  Collection<int, User>  $users
     */
    private function seedPayslips(Collection $users): void
    {
        $ref = now()->subMonth();

        foreach ($users as $user) {
            $annual = (float) $user->salary;
            $monthlyBase = round($annual / 12, 2);
            $bonus = round($monthlyBase * random_int(5, 15) / 100, 2);
            $deductions = round($monthlyBase * random_int(8, 14) / 100, 2);
            $net = round($monthlyBase + $bonus - $deductions, 2);

            Payslip::query()->create([
                'user_id' => $user->id,
                'period_month' => (int) $ref->month,
                'period_year' => (int) $ref->year,
                'base_amount' => $monthlyBase,
                'bonus' => $bonus,
                'deductions' => $deductions,
                'net_total' => $net,
                'pdf_path' => null,
            ]);
        }
    }

    /**
     * @param  Collection<int, User>  $users
     */
    private function seedReviews(Collection $users, ?User $admin): void
    {
        if ($users->isEmpty()) {
            return;
        }

        $periods = ReviewPeriod::ALL;
        $feedbackSamples = [
            'Strong ownership and clear communication in cross-functional work.',
            'Consistently meets commitments; could delegate more to grow the team.',
            'Excellent stakeholder alignment; continue focusing on documentation.',
            'Reliable delivery; opportunity to take on larger technical initiatives.',
        ];

        foreach ($users as $i => $reviewee) {
            if ($admin && $admin->id !== $reviewee->id) {
                Review::query()->create([
                    'user_id' => $reviewee->id,
                    'reviewer_id' => $admin->id,
                    'rating' => round(random_int(380, 500) / 100, 2),
                    'review_period' => $periods[$i % count($periods)],
                    'feedback_text' => $feedbackSamples[$i % count($feedbackSamples)],
                ]);
            }

            $peers = $users->filter(fn(User $u) => $u->id !== $reviewee->id
                && $u->department_id === $reviewee->department_id);

            $peer = $peers->isNotEmpty() ? $peers->random() : $users->first(fn(User $u) => $u->id !== $reviewee->id);

            if ($peer && $peer->id !== $reviewee->id) {
                Review::query()->create([
                    'user_id' => $reviewee->id,
                    'reviewer_id' => $peer->id,
                    'rating' => round(random_int(350, 500) / 100, 2),
                    'review_period' => $periods[($i + 1) % count($periods)],
                    'feedback_text' => $feedbackSamples[($i + 2) % count($feedbackSamples)],
                ]);
            }
        }
    }
}
