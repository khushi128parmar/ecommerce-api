<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    use ApiResponseTrait;

    // CART LIST
    public function index(Request $request)
    {
        $cartItems = Cart::with('product')

            ->where('user_id', $request->user()->id)

            ->get();

        $total = $cartItems->sum(function ($item) {

            return $item->price * $item->quantity;
        });

        return $this->successResponse(
            'Cart fetched successfully',
            [
                'items' => $cartItems,
                'total' => $total
            ]
        );
    }

    // ADD TO CART
    public function store(Request $request)
    {
        $request->validate([

            'product_id' => 'required|exists:products,id',

            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // STOCK VALIDATION
        if ($product->stock < $request->quantity) {

            return $this->errorResponse(
                'Insufficient stock',
                400
            );
        }

        $cart = Cart::where('user_id', $request->user()->id)

            ->where('product_id', $product->id)

            ->first();

        // PRODUCT ALREADY EXISTS
        if ($cart) {

            $newQuantity = $cart->quantity + $request->quantity;

            if ($product->stock < $newQuantity) {

                return $this->errorResponse(
                    'Stock limit exceeded',
                    400
                );
            }

            $cart->update([
                'quantity' => $newQuantity
            ]);

        } else {

            $cart = Cart::create([

                'user_id' => $request->user()->id,

                'product_id' => $product->id,

                'quantity' => $request->quantity,

                'price' => $product->price
            ]);
        }

        return $this->successResponse(
            'Product added to cart',
            $cart
        );
    }

    // UPDATE QUANTITY
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($cart->product->stock < $request->quantity) {

            return $this->errorResponse(
                'Insufficient stock',
                400
            );
        }

        $cart->update([
            'quantity' => $request->quantity
        ]);

        return $this->successResponse(
            'Cart updated successfully',
            $cart
        );
    }

    // REMOVE ITEM
    public function destroy(Cart $cart)
    {
        $cart->delete();

        return $this->successResponse(
            'Item removed successfully'
        );
    }
}