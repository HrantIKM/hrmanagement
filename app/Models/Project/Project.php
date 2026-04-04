<?php

namespace App\Models\Project;

use App\Models\Base\BaseModel;
use App\Models\Base\Traits\HasFileData;
use App\Models\File\File;
use App\Models\Project\Enums\ProjectStatus;
use App\Models\Project\Traits\ProjectRelations;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends BaseModel
{
    use HasFileData;
    use ProjectRelations;

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
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    public array $defaultValues = [
        'status' => ProjectStatus::PLANNING,
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

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

    protected function statusDisplay(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status
                ? __('project.status.' . $this->status)
                : ''
        );
    }
}
