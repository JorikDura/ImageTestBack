<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ImageRequest;
use App\Http\Requests\Api\V1\ImageStoreRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Images\Interfaces\ImageService;
use Modules\Images\Models\Image;
use Modules\Images\Resources\ImageResource;

class ImageController extends Controller
{
    public function __construct(
        protected readonly ImageService $service
    ) {
    }

    public function index(ImageRequest $request): AnonymousResourceCollection
    {
        return $this->service->index($request);
    }

    public function store(ImageStoreRequest $request): JsonResponse
    {
        return $this->service->translate($request);
    }

    public function show(Image $image): ImageResource
    {
        return ImageResource::make($image);
    }

    public function destroy(Image $image): JsonResponse
    {
        return $this->service->destroy($image);
    }
}
