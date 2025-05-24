<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Repository\S3StorageRepository;
use App\Infrastructure\Interface\FileStorageRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app()->bind(FileStorageRepositoryInterface::class, S3StorageRepository::class);
    }
}
