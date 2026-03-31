<?php

namespace App\Models\Base\Traits;

use App\Models\File\File;
use Exception;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasFileData
{
    abstract public function setFileConfigName(): string;

    /**
     * Function to get model file config name.
     */
    public function getFileConfigName(): string
    {
        $configKey = "files.{$this->setFileConfigName()}";
        if (!config()->has($configKey)) {
            throw new Exception("Config: $configKey Not found");
        }

        return $this->setFileConfigName();
    }

    /**
     * Function to get model files (morph table).
     */
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
