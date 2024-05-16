<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Modules\Images\Models\Image;

class ImageStoreRequest extends FormRequest
{
    private const array letters = [
        'ж' => 'zh',
        'ч' => 'ch',
        'щ' => 'shh',
        'ш' => 'sh',
        'ю' => 'yu',
        'ё' => 'yo',
        'я' => 'ya',
        'э' => 'e',
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'з' => 'z',
        'и' => 'i',
        'й' => 'y',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'c',
        'ъ' => '',
        'ь' => '',
        'ы' => 'i',
    ];

    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'name.*' => ['required'],
        ];
    }

    public function prepareForValidation(): void
    {
        $images = $this->images;

        if (is_null($images)) {
            return;
        }

        $result = [];

        foreach ($images as $image) {
            $imageName = Str::lower($image->getClientOriginalName());
            $imageName = Str::squish($imageName);
            $imageName = preg_replace("/[^A-Za-z0-9.'\-]/", '', $imageName);
            $imageName = Str::replace(' ', '-', $imageName);
            $imageNameTranslate = str_replace(
                array_keys(self::letters),
                array_values(self::letters),
                $imageName
            );

            $imageNameWithoutExtension = pathinfo($imageNameTranslate, PATHINFO_FILENAME);
            $extension = pathinfo($imageNameTranslate, PATHINFO_EXTENSION);
            $imagesInDb = Image::select('name')
                ->where('name', 'LIKE', $imageNameWithoutExtension.'%')
                ->latest()
                ->get();

            if ($imagesInDb->count() > 0 && $imagesInDb->last()->name === $imageNameTranslate) {
                $count = $imagesInDb->count();
                $result[] = [
                    'original' => "$imageNameWithoutExtension-$count.$extension",
                    'scaled' => "$imageNameWithoutExtension-$count-scaled.$extension",
                ];
            } else {
                $result[] = [
                    'original' => $imageNameTranslate,
                    'scaled' => "$imageNameWithoutExtension-scaled.$extension",
                ];
            }
        }

        $this->merge([
            'name' => $result,
        ]);
    }
}
