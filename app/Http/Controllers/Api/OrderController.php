<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    use ApiResponseTrait;

    // MY ORDERS
    public function index(Request $request)
    {
        $orders = Order::with([
                'items.product',
                'address'
            ])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return $this->successResponse(
            'Orders fetched successfully',
            OrderResource::collection($orders)
        );
    }

    // SINGLE ORDER
    public function show(Order $order)
    {
        // SECURITY CHECK
        if ($order->user_id !== auth()->id()) {

            return $this->errorResponse(
                'Unauthorized access',
                403
            );
        }

        $order->load([
            'items.product',
            'address'
        ]);

        return $this->successResponse(
            'Order fetched successfully',
            new OrderResource($order)
        );
    }

    // CANCEL ORDER
    public function destroy(Order $order)
    {
        // SECURITY CHECK
        if ($order->user_id !== auth()->id()) {

            return $this->errorResponse(
                'Unauthorized access',
                403
            );
        }

        // ONLY PENDING CAN CANCEL
        if ($order->status !== 'pending') {

            return $this->errorResponse(
                'Only pending orders can be cancelled',
                400
            );
        }

        // RESTORE STOCK
        foreach ($order->items as $item) {

            $item->product->increment(
                'stock',
                $item->quantity
            );
        }

        // UPDATE STATUS
        $order->update([
            'status' => 'cancelled'
        ]);

        return $this->successResponse(
            'Order cancelled successfully'
        );
    }

    // ADMIN STATUS UPDATE
    public function updateStatus(
        Request $request,
        Order $order
    ) {

        $request->validate([

            'status' => 'required|in:
                pending,
                processing,
                shipped,
                delivered,
                cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return $this->successResponse(
            'Order status updated successfully',
            new OrderResource($order)
        );
    }
}