<?php

use App\Models\Article\Article;
use App\Models\Department\Department;
use App\Models\File\Enums\FileType;
use App\Models\Project\Project;
use App\Models\User\User;

return [
    Project::getClassName() => [
        'icon' => [
            'field_name' => 'icon',
            'file_type' => FileType::IMAGE,
            'validation' => 'nullable|mimes:jpg,jpeg,png,bmp,tiff,webp,svg|max:4096',
        ],
    ],

    Department::getClassName() => [
        'icon' => [
            'field_name' => 'icon',
            'file_type' => FileType::IMAGE,
            'validation' => 'nullable|mimes:jpg,jpeg,png,bmp,tiff,webp,svg|max:4096',
        ],
    ],

    User::getClassName() => [
        'signature' => [
            'field_name' => 'signature',
            'file_type' => FileType::IMAGE,
            'validation' => 'nullable|mimes:jpg,jpeg,png,bmp,tiff|max:4096',
            //            'multiple' => true
        ],

        'avatar' => [
            'field_name' => 'avatar',
            'file_type' => FileType::IMAGE,
            'validation' => 'nullable|mimes:jpg,jpeg,png,bmp,tiff|max:4096',
            'is_cropped' => true,
        ],
    ],

    Article::getClassName() => [
        'photo' => [
            'field_name' => 'photo',
            'file_type' => FileType::IMAGE,
            'validation' => 'nullable|mimes:jpg,jpeg,png,bmp,tiff|max:4096',
            /*'thumb' => [
                [
                    'width' => 300,
                    'height' => 200,
                ],
                [
                    'width' => 400,
                    'height' => '',
                ],
            ]*/
        ],
    ],
];
