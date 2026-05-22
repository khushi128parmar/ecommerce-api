<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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

            'order_number' => $this->order_number,

            'status' => $this->status,

            'payment_method' => $this->payment_method,

            'payment_status' => $this->payment_status,

            'total_amount' => $this->total_amount,

            'address' => [

                'full_name' => $this->address?->full_name,

                'phone' => $this->address?->phone,

                'city' => $this->address?->city,

                'state' => $this->address?->state,

                'country' => $this->address?->country,
            ],

            'items' => $this->items->map(function ($item) {

                return [

                    'product_id' => $item->product?->id,

                    'product_name' => $item->product?->name,

                    'quantity' => $item->quantity,

                    'price' => $item->price,

                    'subtotal' => $item->subtotal,
                ];
            }),

            'created_at' => $this->created_at,
        ];
    }
}
