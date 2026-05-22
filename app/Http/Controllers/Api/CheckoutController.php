<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Jobs\SendOrderNotificationJob;

class CheckoutController extends Controller
{
    use ApiResponseTrait;


    public function checkout(CheckoutRequest $request)
    {
        DB::beginTransaction();

        try {

            // GET USER CART ITEMS
            $cartItems = Cart::with('product')

                ->where('user_id', $request->user()->id)

                ->get();

            // CHECK EMPTY CART
            if ($cartItems->isEmpty()) {

                return $this->errorResponse(
                    'Cart is empty',
                    400
                );
            }

            $totalAmount = 0;

            // STOCK VALIDATION
            foreach ($cartItems as $item) {

                if ($item->product->stock < $item->quantity) {

                    return $this->errorResponse(
                        $item->product->name . ' stock not available',
                        400
                    );
                }

                $totalAmount += (
                    $item->price * $item->quantity
                );
            }

            // CREATE ORDER
            $order = Order::create([

                'user_id' => $request->user()->id,

                'address_id' => $request->address_id,

                'order_number' => 'ORD-' . strtoupper(Str::random(10)),

                'total_amount' => $totalAmount,

                'payment_method' => $request->payment_method,

                'payment_status' => 'pending',

                'status' => 'pending',
            ]);

            SendOrderNotificationJob::dispatch(
                auth()->user(),
                $order
            );
            // CREATE ORDER ITEMS
            foreach ($cartItems as $item) {

                OrderItem::create([

                    'order_id' => $order->id,

                    'product_id' => $item->product_id,

                    'quantity' => $item->quantity,

                    'price' => $item->price,

                    'subtotal' => (
                        $item->price * $item->quantity
                    ),
                ]);

                // REDUCE STOCK
                $product = Product::find($item->product_id);

                $product->decrement(
                    'stock',
                    $item->quantity
                );
            }

            // CLEAR CART
            Cart::where(
                'user_id',
                $request->user()->id
            )->delete();

            DB::commit();

            return $this->successResponse(
                'Order placed successfully',
                $order->load([
                    'items.product',
                    'address'
                ])
            );
        } catch (\Exception $e) {

            DB::rollBack();

            return $this->errorResponse(
                $e->getMessage(),
                500
            );
        }
    }
}
