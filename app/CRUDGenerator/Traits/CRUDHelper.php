<?php

namespace App\CRUDGenerator\Traits;

use Illuminate\Support\Facades\Storage;

trait CRUDHelper
{
    /**
     * Function to create file.
     */
    public function createFolderAndFile(array $data): void
    {
        $disk = Storage::disk('base');
        $fileInfo = $data['fileInfo'];
        $variables = $data['variables'];
        $fileName = $fileInfo['file_name'];
        $fileName = $this->replaceAttributeByClassName($fileName);
        $path = $this->replaceAttributeByClassName($this->config['path'], $variables['CLASS_NAME'] ?? '');

        $absolutePath = $path . '\\' . $fileName;

        if (!$disk->exists($absolutePath)) {
            $disk->put($absolutePath, $data['content']);
        }
    }

    /**
     * Function to replace :attribute to class name.
     */
    protected function replaceAttributeByClassName(string $fileName, $CLASS_NAME = null): string
    {
        return str_replace(':attribute', $CLASS_NAME ?? $this->className, $fileName);
    }
}
