<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    use ApiResponseTrait;

    // PRODUCT REVIEWS
    public function index($productId)
    {
        $reviews = Review::with('user')

            ->where('product_id', $productId)

            ->latest()

            ->paginate(10);

        return $this->successResponse(
            'Reviews fetched successfully',
            ReviewResource::collection($reviews)
        );
    }

    // ADD REVIEW
    public function store(ReviewRequest $request)
    {
        // VERIFIED PURCHASE CHECK
        $purchased = OrderItem::whereHas(
                'order',
                function ($query) {

                    $query->where(
                        'user_id',
                        auth()->id()
                    )
                    ->where(
                        'status',
                        'delivered'
                    );
                }
            )
            ->where(
                'product_id',
                $request->product_id
            )
            ->exists();

        if (!$purchased) {

            return $this->errorResponse(
                'You can review only purchased products',
                400
            );
        }

        // ONE REVIEW PER USER
        $alreadyReviewed = Review::where(
                'user_id',
                auth()->id()
            )
            ->where(
                'product_id',
                $request->product_id
            )
            ->exists();

        if ($alreadyReviewed) {

            return $this->errorResponse(
                'You already reviewed this product',
                400
            );
        }

        $review = Review::create([

            'user_id' => auth()->id(),

            'product_id' => $request->product_id,

            'rating' => $request->rating,

            'review' => $request->review,
        ]);

        return $this->successResponse(
            'Review added successfully',
            new ReviewResource(
                $review->load('user')
            )
        );
    }

    // DELETE REVIEW
    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {

            return $this->errorResponse(
                'Unauthorized access',
                403
            );
        }

        $review->delete();

        return $this->successResponse(
            'Review deleted successfully'
        );
    }
}