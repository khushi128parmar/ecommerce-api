<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductVariantRequest;

class ProductVariantController extends Controller
{
    use ApiResponseTrait;

    public function index($productId)
    {
        $variants = ProductVariant::where(
                'product_id',
                $productId
            )
            ->latest()
            ->get();

        return $this->successResponse(
            'Variants fetched successfully',
            $variants
        );
    }

    public function store(ProductVariantRequest $request)
    {
        $variant = ProductVariant::create([

            'product_id' => $request->product_id,

            'sku' => $request->sku,

            'size' => $request->size,

            'color' => $request->color,

            'price' => $request->price,

            'stock' => $request->stock,
        ]);

        return $this->successResponse(
            'Variant created successfully',
            $variant
        );
    }

    public function update(
        ProductVariantRequest $request,
        ProductVariant $productVariant
    ) {

        $productVariant->update([

            'sku' => $request->sku,

            'size' => $request->size,

            'color' => $request->color,

            'price' => $request->price,

            'stock' => $request->stock,
        ]);

        return $this->successResponse(
            'Variant updated successfully',
            $productVariant
        );
    }

    public function destroy(
        ProductVariant $productVariant
    ) {

        $productVariant->delete();

        return $this->successResponse(
            'Variant deleted successfully'
        );
    }
}