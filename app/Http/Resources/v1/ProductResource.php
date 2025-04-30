<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),

            'store' => [
                'id' => $this->store->id,
                'name' => $this->store->name,
                'description' => $this->store->description,
                'university' => $this->store->university->name ?? null,
                'owner_phone' => $this->store->user->phone ?? null,
            ],

            'category' => $this->category->name ?? null,

            'images' => ImageResource::collection($this->images),
        ];
    }
}
