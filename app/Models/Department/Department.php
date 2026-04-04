<?php

namespace App\Models\Department;

use App\Models\Base\BaseModel;
use App\Models\Base\Traits\HasFileData;
use App\Models\Department\Traits\DepartmentRelations;
use App\Models\File\File;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Department extends BaseModel
{
    use DepartmentRelations;
    use HasFileData;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
    ];

    public function setFileConfigName(): string
    {
        return self::getClassName();
    }

    public function files(?string $fieldName = null, ?string $fileType = null): MorphMany
    {
        return $this->morphMany(File::class, 'fileable')
            ->when($fieldName, function ($query) use ($fieldName) {
                $query->where('field_name', $fieldName);
            })
            ->when($fileType, function ($query) use ($fileType) {
                $query->where('file_type', $fileType);
            });
    }
}
