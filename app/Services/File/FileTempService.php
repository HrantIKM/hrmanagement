<?php

namespace App\Services\File;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;

class FileTempService extends FileService
{
    public function storeFile(Model $model, array $data): void
    {
        foreach ($model->getFileConfig() as $fieldName => $config) {
            $files = $data[$fieldName] ?? [];
            $files = is_array($files) ? $files : [$files];

            foreach ($files as $fileName) {
                $this->create($model, $config, $fileName);
            }
        }
    }

    private function create(Model $model, array $config, string $fileName): void
    {
        $fieldName = $config['field_name'];
        $fileType = $config['file_type'];

        $dirPrefix = $model::getClassName();
        $path = $dirPrefix . '/' . $fieldName;
        $fileBaseName = explode('/', $fileName)[1] ?? null;

        // temp file move upload
        if ($fileBaseName) {
            if (!isset($config['multiple'])) {
                $this->deleteModelFile($model, $config['field_name']);
            }

            $move = $this->movePendingFileToUploadsFolder(fileName: $fileBaseName, config: $config, directoryData: [
                'pending' => $fileName,
                'uploads' => $path,
            ]);

            if ($move) {
                $model->files($fieldName)->create([
                    'id' => Uuid::uuid4(),
                    'field_name' => $fieldName,
                    'file_name' => $fileBaseName,
                    'file_type' => $fileType,
                    'dir_prefix' => $dirPrefix,
                ]);
            }
        }
    }

    public function storeTempFile(array $data): array
    {
        $file = $data['file'];
        $config = config("files.{$data['config_key']}");

        if (isset($config['is_cropped'])) {
            $file = $this->getCroppedFile($data);
        }

        $filename = $this->getFileName($file);
        $path = now()->format('d-m-Y');

        $this->pendingDisk->putFileAs(path: $path, file: $file, name: $filename);

        return [
            'status' => 'OK',
            'file_url' => $this->pendingDisk->url($path . '/' . $filename),
            'name' => $path . '/' . $filename,
            'original_name' => $file->getClientOriginalName(),
            'file_type' => $config['file_type'],
        ];
    }

    /**
     * Function to from base_64 get UploadedFile type.
     */
    private function getCroppedFile(array $data): UploadedFile
    {
        $file = $data['file'];
        $fileName = $data['name'];

        $file = str_replace('data:image/png;base64,', '', $file);

        //        $extension = explode('/', mime_content_type($file))[1];

        $croppedImageFileName = now()->format('d-m-Y') . '/' . $fileName;

        $tmpFilePath = $this->pendingDisk->path($croppedImageFileName);
        $this->makeDirectory($tmpFilePath);

        file_put_contents($tmpFilePath, base64_decode($file));

        $tmpFile = new File($tmpFilePath);

        return new UploadedFile(
            $tmpFile->getPathname(),
            $tmpFile->getFilename(),
            $tmpFile->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );
    }

    /**
     * Function to remove without current day temp files.
     */
    public static function removeTempFiles(): void
    {
        $disk = Storage::disk('pending');
        $pastDay = now()->subDay();
        $directories = $disk->allDirectories();

        foreach ($directories as $directory) {
            $directoryDate = Carbon::parse($directory);
            if (!$pastDay->lt($directoryDate)) {
                $disk->deleteDirectory($directory);
            }
        }
    }

    /**
     * Function to move files from pending to uploads.
     */
    public function moveToUploadsFolder(string $fileName, string $customDirectory = '', array $config = []): string
    {
        $fileBaseName = explode('/', $fileName)[1] ?? null;

        $this->movePendingFileToUploadsFolder(fileName: $fileBaseName, config: $config, directoryData: [
            'pending' => $fileName,
            'uploads' => $customDirectory,
        ]);

        return $fileBaseName;
    }
}
