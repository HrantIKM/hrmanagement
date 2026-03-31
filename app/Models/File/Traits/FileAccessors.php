<?php

namespace App\Models\File\Traits;

use Illuminate\Support\Facades\Storage;

trait FileAccessors
{
    public function getFileUrlAttribute(): string
    {
        return Storage::disk('uploads')
            ->url($this->dir_prefix . '/' . $this->field_name . '/' . $this->file_name);
    }

    public function getFilePathAttribute(): string
    {
        return Storage::disk('uploads')
            ->path($this->dir_prefix . '/' . $this->field_name . '/' . $this->file_name);
    }

    public function getFileOriginalNameAttribute(): string
    {
        $explodedFileName = explode('_', $this->file_name, 2);

        return $explodedFileName[1] ?? '';
    }
}
