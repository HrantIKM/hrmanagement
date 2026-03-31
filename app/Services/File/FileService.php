<?php

namespace App\Services\File;

use App\Repositories\File\FileRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

// use Intervention\Image\Facades\Image;

abstract class FileService
{
    protected Filesystem $uploadsDisk;

    protected Filesystem $pendingDisk;

    protected Filesystem $awsDisk;

    public function __construct(
        protected FileRepository $repository
    ) {
        $this->setDisks();
    }

    /**
     * Function to move tmp file from pending dir to upload dir.
     */
    protected function movePendingFileToUploadsFolder(
        string $fileName,
        array $config = [],
        array $directoryData = []
    ): bool {
        if ($this->pendingDisk->exists($directoryData['pending'])) {
            // convert to full paths
            $fullPathPending = $this->getFilePathPendingDisk($directoryData['pending']);
            $filePath = isset($directoryData['uploads'])
                ? '/' . $directoryData['uploads'] . '/' . $fileName
                : $fileName;
            $fullPathUploads = $this->getFilePathUploadsDisk($filePath);

            // make destination folder
            $this->makeDirectory($fullPathUploads);

            if (isAwsFilesystem() && !isset($config['disk'])) {
                return $this->awsDisk->move($fullPathPending, $fullPathUploads);
            }

            // save thumb
            if (isset($config['thumb'])) {
                $this->saveThumb(
                    fileName: $fileName,
                    filePath: $fullPathPending,
                    thumbConfig: $config['thumb'],
                    directoryData: $directoryData
                );
            }

            return File::move($fullPathPending, $fullPathUploads);
        }

        return false;
    }

    protected function saveThumb(
        string $fileName,
        string $filePath,
        array $thumbConfig,
        array $directoryData = []
    ): void {
        foreach ($thumbConfig as $thumb) {
            $thumbWidth = $thumb['width'];
            $thumbHeight = $thumb['height'] ?? null;

            $thumbResizePath = $this->getThumbResizePath($thumbWidth, $thumbHeight);

            $fileThumbPath = isset($directoryData['uploads'])
                ? '/' . $directoryData['uploads'] . '/thumbs/' . "$thumbResizePath/" . $fileName
                : '/thumbs/' . $fileName;

            $fullThumbPathUploads = $this->getFilePathUploadsDisk($fileThumbPath);

            $this->makeDirectory($fullThumbPathUploads);

            $thumbImage = Image::make($filePath)->resize($thumbWidth, $thumbHeight, function ($constraint) {
                $constraint->aspectRatio();
            });

            $thumbImage->save($fullThumbPathUploads);
        }
    }

    public function deleteModelFile(Model $model, ?string $fieldName = null): void
    {
        $files = $model->files($fieldName)->get();
        if ($files->count()) {
            foreach ($files as $file) {
                $this->deleteFilePhysically($file);
            }
            $model->files($fieldName)->delete();
        }
    }

    public function deleteFile(string $id): void
    {
        $file = $this->repository->findOrFail($id);
        $this->deleteFilePhysically($file);
        $this->repository->destroy($id);
    }

    private function deleteFilePhysically($file): void
    {
        $this->uploadsDisk->delete($file->dir_prefix . '/' . $file->field_name . '/' . $file->file_name);

        if ($this->uploadsDisk->exists($file->dir_prefix . '/' . $file->field_name . '/thumbs/')) {
            $config = config("files.$file->dir_prefix.$file->field_name");

            if (isset($config['thumb'])) {
                foreach ($config['thumb'] as $thumb) {
                    $thumbResizePath = $this->getThumbResizePath($thumb['width'], $thumb['height'] ?? null);
                    $thumbFilePath = $file->dir_prefix . '/' . $file->field_name . '/thumbs/' . $thumbResizePath;
                    $thumbFilePath .= '/' . $file->file_name;

                    if ($this->uploadsDisk->exists($thumbFilePath)) {
                        $this->uploadsDisk->delete($thumbFilePath);
                    }
                }
            }
        }
    }

    private function getThumbResizePath(string $thumbWidth, ?string $thumbHeight): string
    {
        $thumbResizePath = $thumbWidth;
        if ($thumbHeight) {
            $thumbResizePath .= '_' . $thumbHeight;
        }

        return $thumbResizePath;
    }

    protected function getFileName(UploadedFile $file): string
    {
        $originalName = $file->getClientOriginalName();
        $filename = basename($originalName, '.' . pathinfo($originalName, PATHINFO_EXTENSION));
        $uniqueID = uniqid() . '_' . mb_ereg_replace('([^\\w\\s\\d\\-_~,;\\[\\]\\(\\).])', '', $filename);

        return $uniqueID . '.' . $file->getClientOriginalExtension();
    }

    protected function makeDirectory(string $path): void
    {
        if (!File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }
    }

    protected function getFilePathUploadsDisk(string $path): string
    {
        return $this->uploadsDisk->path($path);
    }

    protected function getFilePathPendingDisk(string $path): string
    {
        return $this->pendingDisk->path($path);
    }

    private function setDisks(): void
    {
        $this->uploadsDisk = Storage::disk('uploads');
        $this->pendingDisk = Storage::disk('pending');

        if (isAwsFilesystem()) {
            $this->awsDisk = Storage::disk('s3');
        }
    }
}
