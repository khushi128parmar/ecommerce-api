<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;

class CouponApplyController extends Controller
{
    use ApiResponseTrait;

    public function apply(Request $request)
    {
        $request->validate([

            'code' => 'required|string'
        ]);

        $coupon = Coupon::where(
                'code',
                $request->code
            )
            ->where('status', true)
            ->first();

        // INVALID COUPON
        if (!$coupon) {

            return $this->errorResponse(
                'Invalid coupon code',
                400
            );
        }

        // EXPIRED CHECK
        if (
            $coupon->expires_at &&
            now()->gt($coupon->expires_at)
        ) {

            return $this->errorResponse(
                'Coupon expired',
                400
            );
        }

        // USAGE LIMIT CHECK
        if (
            $coupon->usage_limit !== null &&
            $coupon->usage_limit <= 0
        ) {

            return $this->errorResponse(
                'Coupon usage limit exceeded',
                400
            );
        }

        // CART TOTAL
        $cartTotal = Cart::where(
                'user_id',
                auth()->id()
            )
            ->sum('subtotal');

        // MINIMUM AMOUNT CHECK
        if (
            $coupon->minimum_amount &&
            $cartTotal < $coupon->minimum_amount
        ) {

            return $this->errorResponse(
                'Minimum order amount not reached',
                400
            );
        }

        // DISCOUNT
        $discount = 0;

        if ($coupon->type === 'fixed') {

            $discount = $coupon->value;

        } else {

            $discount = (
                $cartTotal *
                $coupon->value
            ) / 100;
        }

        // FINAL AMOUNT
        $finalAmount = $cartTotal - $discount;

        return $this->successResponse(
            'Coupon applied successfully',
            [

                'coupon_code' => $coupon->code,

                'cart_total' => $cartTotal,

                'discount' => $discount,

                'final_amount' => $finalAmount
            ]
        );
    }
}