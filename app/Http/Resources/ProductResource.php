<?php

namespace App\Http\Resources;

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

            'slug' => $this->slug,

            'description' => $this->description,

            'price' => $this->price,

            'stock' => $this->stock,

            'sku' => $this->sku,

            'status' => $this->status,

            'image' => $this->image
                ? asset('storage/' . $this->image)
                : null,

            'average_rating' => $this->average_rating,

            'total_reviews' => $this->total_reviews,
            
            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],

            'brand' => [
                'id' => $this->brand?->id,
                'name' => $this->brand?->name,
            ],

            'created_at' => $this->created_at,



        ];
    }
}
