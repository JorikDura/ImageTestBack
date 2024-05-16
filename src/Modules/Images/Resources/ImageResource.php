<?php

declare(strict_types=1);

namespace Modules\Images\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'originalImageUrl' => asset('storage/images/'.$this->name),
            'smallImageUrl' => ! is_null($this->name_scaled) ? asset('storage/images/'.$this->name_scaled) : null,
            'createdAt' => $this->created_at,
        ];
    }
}
