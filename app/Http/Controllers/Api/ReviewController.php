<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    use ApiResponseTrait;

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

    public function store(ReviewRequest $request)
    {
        DB::beginTransaction();

        try {

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


            $this->updateProductRating(
                $review->product_id
            );

            DB::commit();

            return $this->successResponse(
                'Review added successfully',
                new ReviewResource(
                    $review->load('user')
                )
            );
        } catch (\Exception $e) {

            DB::rollBack();

            return $this->errorResponse(
                $e->getMessage(),
                500
            );
        }
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {

            return $this->errorResponse(
                'Unauthorized access',
                403
            );
        }

        DB::beginTransaction();

        try {

            $productId = $review->product_id;

            $review->delete();

            $this->updateProductRating(
                $productId
            );

            DB::commit();

            return $this->successResponse(
                'Review deleted successfully'
            );
        } catch (\Exception $e) {

            DB::rollBack();

            return $this->errorResponse(
                $e->getMessage(),
                500
            );
        }
    }

    private function updateProductRating($productId)
    {
        $product = Product::find($productId);

        $averageRating = Review::where(
            'product_id',
            $productId
        )
            ->avg('rating') ?? 0;

        $totalReviews = Review::where(
            'product_id',
            $productId
        )
            ->count();

        $product->update([

            'average_rating' => round(
                $averageRating,
                1
            ),

            'total_reviews' => $totalReviews
        ]);
    }
}
