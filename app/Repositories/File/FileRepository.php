<?php

namespace App\Repositories\File;

use App\Contracts\File\IFileRepository;
use App\Models\File\File;
use App\Repositories\BaseRepository;

class FileRepository extends BaseRepository implements IFileRepository
{
    public function __construct(File $model)
    {
        parent::__construct($model);
    }
}
