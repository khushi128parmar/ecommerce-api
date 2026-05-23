<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    use ApiResponseTrait;

    public function cod(Order $order)
    {
        if ($order->user_id !== Auth::id()) {

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

        if ($order->user_id !== Auth::id()) {

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
        if ($order->user_id !== Auth::id()) {

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

    public function stripeCheckout(Order $order)
    {
        Stripe::setApiKey(
            env('STRIPE_SECRET')
        );

        $session = Session::create([

            'payment_method_types' => ['card'],

            'line_items' => [[

                'price_data' => [

                    'currency' => 'inr',

                    'product_data' => [

                        'name' => 'Order #' . $order->id,
                    ],

                    'unit_amount' => (
                        $order->total * 100
                    ),
                ],

                'quantity' => 1,
            ]],

            'mode' => 'payment',

            'success_url' => url(
                '/payment-success'
            ),

            'cancel_url' => url(
                '/payment-failed'
            ),
        ]);

        return response()->json([

            'checkout_url' => $session->url
        ]);
    }

   public function webhook(Request $request)
{
    $payload = $request->getContent();

    $sigHeader = $request->server(
        'HTTP_STRIPE_SIGNATURE'
    );

    $secret = env(
        'STRIPE_WEBHOOK_SECRET'
    );

    try {

        $event = Webhook::constructEvent(

            $payload,

            $sigHeader,

            $secret
        );

    } catch (\Exception $e) {

        return response()->json([

            'success' => false,

            'message' => 'Invalid webhook'
        ], 400);
    }

    return response()->json([

        'success' => true,

        'event' => $event->type
    ]);
}
}
