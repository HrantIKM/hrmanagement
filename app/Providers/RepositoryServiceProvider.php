<?php

namespace App\Providers;

use App\Contracts\Article\IArticleRepository;
use App\Contracts\File\IFileRepository;
use App\Contracts\Role\IRoleRepository;
use App\Contracts\User\IUserRepository;
use App\Repositories\Article\ArticleRepository;
use App\Repositories\File\FileRepository;
use App\Repositories\Role\RoleRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array|string[]
     */
    public array $singletons = [
        IUserRepository::class => UserRepository::class,
        IRoleRepository::class => RoleRepository::class,
        IFileRepository::class => FileRepository::class,
        IArticleRepository::class => ArticleRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
