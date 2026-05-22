<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;

class OrderStatusController extends Controller
{
    use ApiResponseTrait;

    public function updateStatus(
        Request $request,
        Order $order
    ) {

        $request->validate([

            'order_status' => 'required|in:
                pending,
                confirmed,
                shipped,
                delivered,
                cancelled'
        ]);

        $order->update([

            'order_status' => $request->order_status
        ]);

        return $this->successResponse(
            'Order status updated successfully',
            $order
        );
    }
}