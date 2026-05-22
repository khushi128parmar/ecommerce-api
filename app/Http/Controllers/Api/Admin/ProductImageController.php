<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImageRequest;

class ProductImageController extends Controller
{
    use ApiResponseTrait;

    public function index($productId)
    {
        $images = ProductImage::where(
                'product_id',
                $productId
            )
            ->latest()
            ->get();

        return $this->successResponse(
            'Product images fetched successfully',
            $images
        );
    }

    public function store(ProductImageRequest $request)
    {
        $image = $request->file('image')

            ->store('products', 'public');

        $productImage = ProductImage::create([

            'product_id' => $request->product_id,

            'image' => $image
        ]);

        return $this->successResponse(
            'Product image uploaded successfully',
            $productImage
        );
    }

    public function destroy(ProductImage $productImage)
    {
        $productImage->delete();

        return $this->successResponse(
            'Product image deleted successfully'
        );
    }
}