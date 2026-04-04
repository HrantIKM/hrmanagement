<?php

namespace App\Repositories\Candidate;

use App\Contracts\Candidate\ICandidateRepository;
use App\Repositories\BaseRepository;
use App\Models\Candidate\Candidate;

class CandidateRepository extends BaseRepository implements ICandidateRepository
{
    public function __construct(Candidate $model)
    {
        parent::__construct($model);
    }
}
