<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [

            'order_id' => $this->id,

            'order_number' => $this->order_number,

            'payment_method' => $this->payment_method,

            'payment_status' => $this->payment_status,

            'transaction_id' => $this->transaction_id,

            'total_amount' => $this->total_amount,
        ];
    }
}
