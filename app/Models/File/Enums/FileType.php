<?php

namespace App\Models\File\Enums;

final class FileType
{
    public const IMAGE = 'image';
    public const FILE = 'file';

    public const ALL = [
        self::IMAGE,
        self::FILE,
    ];
}
