<?php

declare(strict_types=1);

namespace Modules\Images\Services;

use App\Http\Requests\Api\V1\ImageRequest;
use App\Http\Requests\Api\V1\ImageStoreRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Intervention\Image\Laravel\Facades\Image as ImageFacade;
use Modules\Images\Interfaces\ImageService;
use Modules\Images\Models\Image;
use Modules\Images\Resources\ImageResource;

class ImageServiceImpl implements ImageService
{
    private const int height = 400;

    public function index(ImageRequest $request): AnonymousResourceCollection
    {
        $images = ! is_null($request->order_by)
            ? Image::orderBy($request->order_by, 'desc')->paginate(16)->appends($request->query())
            : Image::paginate(perPage: 16);

        return ImageResource::collection($images);
    }

    public function translate(ImageStoreRequest $request): JsonResponse
    {
        $images = $request->images;
        $imageNames = $request->name;

        $path = public_path('storage/images/');

        foreach ($images as $key => $image) {
            $imageName = $imageNames[$key]['original'];
            $imageNameScaled = $imageNames[$key]['scaled'];

            $uploadedImage = ImageFacade::read($image);

            $shouldScaleImage = $uploadedImage->height() > self::height;

            Image::create([
                'name' => $imageName,
                'name_scaled' => $shouldScaleImage ? $imageNameScaled : null,
            ]);

            $uploadedImage->save($path.$imageName);

            if ($shouldScaleImage) {
                $uploadedImage->scale(height: self::height)->save($path.$imageNameScaled);
            }
        }

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function destroy(Image $image): JsonResponse
    {
        $image->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
