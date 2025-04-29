<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use App\Http\Resources\v1\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'store' => [
                'id' => $this->store->id,
                'name' => $this->store->name,
                'university' => $this->store->university->name ?? null,
            ],
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->category->name ?? null,
            'images' => ImageResource::collection($this->images),

            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
