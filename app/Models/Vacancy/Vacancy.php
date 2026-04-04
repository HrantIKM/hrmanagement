<?php

namespace App\Models\Vacancy;

use App\Models\Application\Application;
use App\Models\Base\BaseModel;
use App\Models\Candidate\Candidate;
use App\Models\Position\Position;
use App\Models\Skill\Skill;
use App\Models\Vacancy\Enums\VacancyStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vacancy extends BaseModel
{
    /**
     * @var string[]
     */
    protected $appends = [
        'status_display',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'closing_date',
        'position_id',
    ];

    public array $defaultValues = [
        'status' => VacancyStatus::OPEN,
    ];

    protected function casts(): array
    {
        return [
            'closing_date' => 'date',
        ];
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withTimestamps();
    }

    protected function statusDisplay(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status
                ? __('vacancy.status.' . $this->status)
                : ''
        );
    }
}
