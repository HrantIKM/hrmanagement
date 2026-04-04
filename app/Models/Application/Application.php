<?php

namespace App\Models\Application;

use App\Models\Base\BaseModel;
use App\Models\Candidate\Candidate;
use App\Models\Vacancy\Vacancy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends BaseModel
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'vacancy_id',
        'candidate_id',
    ];

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
