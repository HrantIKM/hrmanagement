<?php

namespace App\Models\User\Traits;

use App\Models\Department\Department;
use App\Models\File\File;
use App\Models\Goal\Goal;
use App\Models\Meeting\Meeting;
use App\Models\Payslip\Payslip;
use App\Models\Position\Position;
use App\Models\Project\Project;
use App\Models\Review\Review;
use App\Models\Salary\Salary;
use App\Models\Skill\Skill;
use App\Models\Task\Task;
use App\Models\Timesheet\Timesheet;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait UserRelations
{
    public function avatar(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')->where('field_name', 'avatar');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function timesheets(): HasMany
    {
        return $this->hasMany(Timesheet::class);
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    public function reviewsAsReviewee(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function reviewsAsReviewer(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function salaryHistory(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withTimestamps();
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    public function meetings(): BelongsToMany
    {
        return $this->belongsToMany(Meeting::class)->withTimestamps();
    }
}
