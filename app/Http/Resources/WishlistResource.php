<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
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

            'product' => [

                'id' => $this->product?->id,

                'name' => $this->product?->name,

                'price' => $this->product?->price,

                'stock' => $this->product?->stock,

                'image' => $this->product?->image
                    ? asset('storage/' . $this->product->image)
                    : null,
            ],

            'created_at' => $this->created_at,
        ];
    }
}
