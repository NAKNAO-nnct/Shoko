<?php

declare(strict_types=1);

namespace App\Infrastructure\Interface;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

interface FileStorageRepositoryInterface
{
    public function get(string $path): ?string;

    public function getMetadata(string $path): array;

    public function upload(File|UploadedFile $file): string;

    public function copy(string $sourcePath, string $destinationPath): bool;

    public function move(string $sourcePath, string $destinationPath): bool;

    public function delete(string $path): bool;
}
