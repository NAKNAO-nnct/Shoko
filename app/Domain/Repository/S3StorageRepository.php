<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Infrastructure\Interface\FileStorageRepositoryInterface;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class S3StorageRepository implements FileStorageRepositoryInterface
{
    private Filesystem $storage;

    public function __construct()
    {
        $this->storage = Storage::disk('s3');
    }

    public function get(string $path): ?string
    {
        return $this->storage->get($path);
    }

    public function getMetadata(string $path): array
    {
        return [
            'size' => $this->storage->size($path),
            'type' => $this->storage->mimeType($path),
            'last_modified' => $this->storage->lastModified($path),
        ];
    }

    /**
     * @throws Exception
     */
    public function upload(File|UploadedFile $file): string
    {
        logger()->debug('Uploading file', ['file' => $file->getPathname()]);

        try {
            if (($res = $this->storage->putFile('uploads', $file)) === false) {
                throw new Exception('Failed to upload file');
            }

            logger()->debug('File uploaded successfully', ['path' => $res]);

            return $res;
        } catch (Exception $e) {
            logger()->error('Failed to upload file', ['error' => $e->getMessage()]);

            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function move(string $sourcePath, string $destinationPath): bool
    {
        logger()->debug('Moving file', ['currentPath' => $sourcePath, 'newPath' => $destinationPath]);

        try {
            return $this->storage->move($sourcePath, $destinationPath);
        } catch (Exception $e) {
            logger()->error('Failed to move file', ['error' => $e->getMessage()]);

            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function copy(string $sourcePath, string $destinationPath): bool
    {
        logger()->debug('Copying file', ['srcPath' => $sourcePath, 'destPath' => $destinationPath]);

        try {
            return $this->storage->copy($sourcePath, $destinationPath);
        } catch (Exception $e) {
            logger()->error('Failed to copy file', ['error' => $e->getMessage()]);

            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function delete(string $path): bool
    {
        logger()->debug('Deleting file', ['path' => $path]);

        try {
            return $this->storage->delete($path);
        } catch (Exception $e) {
            logger()->error('Failed to delete file', ['error' => $e->getMessage()]);

            throw $e;
        }
    }
}
