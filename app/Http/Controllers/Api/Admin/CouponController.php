<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Http\Resources\CouponResource;

class CouponController extends Controller
{
    use ApiResponseTrait;

    // LIST
    public function index()
    {
        $coupons = Coupon::latest()

            ->paginate(10);

        return $this->successResponse(
            'Coupons fetched successfully',
            CouponResource::collection($coupons)
        );
    }

    // STORE
    public function store(CouponRequest $request)
    {
        $coupon = Coupon::create(
            $request->validated()
        );

        return $this->successResponse(
            'Coupon created successfully',
            new CouponResource($coupon)
        );
    }

    // SHOW
    public function show(Coupon $coupon)
    {
        return $this->successResponse(
            'Coupon fetched successfully',
            new CouponResource($coupon)
        );
    }

    // UPDATE
    public function update(
        CouponRequest $request,
        Coupon $coupon
    ) {

        $coupon->update(
            $request->validated()
        );

        return $this->successResponse(
            'Coupon updated successfully',
            new CouponResource($coupon)
        );
    }

    // DELETE
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return $this->successResponse(
            'Coupon deleted successfully'
        );
    }
}