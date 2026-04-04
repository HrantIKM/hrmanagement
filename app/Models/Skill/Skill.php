<?php

namespace App\Models\Skill;

use App\Models\Base\BaseModel;
use App\Models\Candidate\Candidate;
use App\Models\Skill\Enums\SkillCategory;
use App\Models\User\User;
use App\Models\Vacancy\Vacancy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends BaseModel
{
    /**
     * @var string[]
     */
    protected $appends = [
        'category_label',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'category',
    ];

    public array $defaultValues = [
        'category' => SkillCategory::TECHNICAL,
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function candidates(): BelongsToMany
    {
        return $this->belongsToMany(Candidate::class)->withTimestamps();
    }

    public function vacancies(): BelongsToMany
    {
        return $this->belongsToMany(Vacancy::class)->withTimestamps();
    }

    protected function categoryLabel(): Attribute
    {
        return new Attribute(
            get: fn () => $this->category
                ? __('skill.category.' . $this->category)
                : ''
        );
    }
}
