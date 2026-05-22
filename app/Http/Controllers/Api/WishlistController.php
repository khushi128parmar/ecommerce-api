<?php

namespace App\Http\Controllers\Api;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\WishlistRequest;
use App\Http\Resources\WishlistResource;

class WishlistController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $wishlists = Wishlist::with('product')

            ->where('user_id', $request->user()->id)

            ->latest()

            ->paginate(10);

        return $this->successResponse(
            'Wishlist fetched successfully',
            WishlistResource::collection($wishlists)
        );
    }

    public function store(WishlistRequest $request)
    {
        $exists = Wishlist::where(
                'user_id',
                $request->user()->id
            )
            ->where(
                'product_id',
                $request->product_id
            )
            ->exists();

        if ($exists) {

            return $this->errorResponse(
                'Product already in wishlist',
                400
            );
        }

        $wishlist = Wishlist::create([

            'user_id' => $request->user()->id,

            'product_id' => $request->product_id
        ]);

        return $this->successResponse(
            'Product added to wishlist',
            new WishlistResource(
                $wishlist->load('product')
            )
        );
    }

    public function destroy(
        Request $request,
        Wishlist $wishlist
    ) {

        if ($wishlist->user_id !== $request->user()->id) {

            return $this->errorResponse(
                'Unauthorized access',
                403
            );
        }

        $wishlist->delete();

        return $this->successResponse(
            'Product removed from wishlist'
        );
    }
}