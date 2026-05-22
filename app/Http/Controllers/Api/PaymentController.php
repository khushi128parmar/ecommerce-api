<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;

class PaymentController extends Controller
{
    use ApiResponseTrait;

    public function cod(Order $order)
    {
        if ($order->user_id !== auth()->id()) {

            return $this->errorResponse(
                'Unauthorized access',
                403
            );
        }

        if ($order->payment_status === 'paid') {

            return $this->errorResponse(
                'Order already paid',
                400
            );
        }

        $order->update([

            'payment_method' => 'cod',

            'payment_status' => 'pending'
        ]);

        return $this->successResponse(
            'Cash on delivery selected',
            new PaymentResource($order)
        );
    }


    public function paymentSuccess(
        PaymentRequest $request,
        Order $order
    ) {

        if ($order->user_id !== auth()->id()) {

            return $this->errorResponse(
                'Unauthorized access',
                403
            );
        }

        if ($order->payment_status === 'paid') {

            return $this->errorResponse(
                'Order already paid',
                400
            );
        }

        $order->update([

            'payment_method' => $request->payment_method,

            'payment_status' => 'paid',

            'transaction_id' => $request->transaction_id
        ]);

        return $this->successResponse(
            'Payment successful',
            new PaymentResource($order)
        );
    }


    public function paymentFailed(Order $order)
    {
        if ($order->user_id !== auth()->id()) {

            return $this->errorResponse(
                'Unauthorized access',
                403
            );
        }

        $order->update([

            'payment_status' => 'failed'
        ]);

        return $this->successResponse(
            'Payment failed',
            new PaymentResource($order)
        );
    }
}