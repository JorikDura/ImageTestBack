<?php

declare(strict_types=1);

namespace Modules\Images\Interfaces;

use App\Http\Requests\Api\V1\ImageRequest;
use App\Http\Requests\Api\V1\ImageStoreRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Images\Models\Image;

interface ImageService
{
    public function index(ImageRequest $request): AnonymousResourceCollection;

    public function translate(ImageStoreRequest $request): JsonResponse;

    public function destroy(Image $image): JsonResponse;
}
