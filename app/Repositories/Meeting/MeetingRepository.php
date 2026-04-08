<?php

namespace App\Repositories\Meeting;

use App\Contracts\Meeting\IMeetingRepository;
use App\Repositories\BaseRepository;
use App\Models\Meeting\Meeting;

class MeetingRepository extends BaseRepository implements IMeetingRepository
{
    public function __construct(Meeting $model)
    {
        parent::__construct($model);
    }
}
