<?php

namespace App\Models\Candidate;

use App\Models\Application\Application;
use App\Models\Base\BaseModel;
use App\Models\Skill\Skill;
use App\Models\Vacancy\Vacancy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends BaseModel
{
    protected $appends = [
        'resume_url',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'full_name',
        'email',
        'resume_path',
        'raw_ai_data',
        'match_score',
        'vacancy_id',
    ];

    protected function casts(): array
    {
        return [
            'raw_ai_data' => 'array',
            'match_score' => 'integer',
        ];
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withTimestamps();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    protected function resumeUrl(): Attribute
    {
        return new Attribute(
            get: function () {
                if (!$this->resume_path) {
                    return null;
                }

                if ($this->id && !app()->runningInConsole()) {
                    try {
                        return route('dashboard.candidates.resume', ['candidate' => $this->id], true);
                    } catch (\Throwable) {
                        // fall through
                    }
                }

                $path = ltrim(str_replace('\\', '/', $this->resume_path), '/');

                return asset('storage/' . $path);
            }
        );
    }
}
