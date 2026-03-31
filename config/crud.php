<?php

$rootNamespace = 'app';

return [
    'controller' => [
        'path' => "$rootNamespace\\Http\\Controllers\\Dashboard",
        'file_name' => ':attributeController.php',
        'stub_file_name' => 'controller',
        'stub_file_name_ml' => 'ml-controller',
    ],

    'interface' => [
        'path' => "$rootNamespace\\Contracts\\:attribute",
        'file_name' => 'I:attributeRepository.php',
        'stub_file_name' => 'interface',
    ],

    'repository' => [
        'path' => "$rootNamespace\\Repositories\\:attribute",
        'file_name' => ':attributeRepository.php',
        'stub_file_name' => 'repository',
    ],

    'service' => [
        'path' => "$rootNamespace\\Services\\:attribute",
        'file_name' => ':attributeService.php',
        'stub_file_name' => 'service',
    ],

    'model' => [
        'path' => "$rootNamespace\\Models\\:attribute",
        'file_name' => ':attribute.php',
        'stub_file_name' => 'model',
        'stub_directory_path' => 'models',
    ],

    'model_with_ml' => [
        'path' => "$rootNamespace\\Models\\:attribute",
        'file_name' => ':attribute.php',
        'stub_file_name' => 'model-with-ml',
        'stub_directory_path' => 'models',
    ],

    'ml_model' => [
        'path' => "$rootNamespace\\Models\\:attribute",
        'file_name' => ':attributeMls.php',
        'stub_file_name' => 'ml-model',
        'stub_directory_path' => 'models',
    ],

    'model_search' => [
        'path' => "$rootNamespace\\Models\\:attribute",
        'file_name' => ':attributeSearch.php',
        'stub_file_name' => 'model.search',
        'stub_file_name_ml' => 'ml-model.search',
        'stub_directory_path' => 'models',
    ],

    'request' => [
        'path' => "$rootNamespace\\Http\\Requests\\:attribute",
        'file_name' => ':attributeRequest.php',
        'stub_file_name' => 'request',
        'stub_file_name_ml' => 'ml-request',
        'stub_directory_path' => 'requests',
    ],

    'search_request' => [
        'path' => "$rootNamespace\\Http\\Requests\\:attribute",
        'file_name' => ':attributeSearchRequest.php',
        'stub_file_name' => 'search.request',
        'stub_directory_path' => 'requests',
    ],

    'blades' => [
        'path' => 'resources\\\\views\\components\\dashboard\\:attribute',
        'files' => [
            [
                'file_name' => 'index.blade.php',
                'stub_file_name' => 'index.blade',
                'stub_file_name_ml' => 'ml-index.blade',
                'stub_directory_path' => 'blades',
            ],
            [
                'file_name' => 'form.blade.php',
                'stub_file_name' => 'form.blade',
                'stub_directory_path' => 'blades',
            ],
        ],
    ],

    'js' => [
        'path' => 'public\\js\\dashboard\\:attribute',
        'files' => [
            [
                'file_name' => 'index.js',
                'stub_file_name' => 'index',
                'stub_directory_path' => 'js',
            ],
            [
                'file_name' => 'main.js',
                'stub_file_name' => 'main',
                'stub_directory_path' => 'js',
            ],
        ],
    ],
];
