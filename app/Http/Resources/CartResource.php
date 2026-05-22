<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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

            'quantity' => $this->quantity,

            'price' => $this->price,

            'subtotal' => $this->price * $this->quantity,

            'product' => [

                'id' => $this->product?->id,

                'name' => $this->product?->name,

                'price' => $this->product?->price,

                'image' => $this->product?->image
                    ? asset('storage/' . $this->product->image)
                    : null,
            ]
        ];
    }
}
