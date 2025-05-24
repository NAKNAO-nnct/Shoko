<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Assets;

use App\Http\Controllers\Controller;
use App\Infrastructure\Interface\FileStorageRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AssetStoreController extends Controller
{
    public function __construct(
        private readonly FileStorageRepositoryInterface $storageRepository,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $file = $request->file('file');

        if (!$file) {
            return response()->json(['error' => 'No file provided'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $path = $this->storageRepository->upload($file);
            $metadata = $this->storageRepository->getMetadata($path);

            return response()->json(['path' => $path, 'metadata' => $metadata], Response::HTTP_CREATED);
        } catch (Exception $e) {
            logger()->error('File upload failed', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'File upload failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
