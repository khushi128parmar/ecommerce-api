<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $products = Product::with([
            'category',
            'brand'
        ])

            ->when($request->search, function ($query) use ($request) {

                $query->where('name', 'LIKE', '%' . $request->search . '%');
            })

            ->when($request->category_id, function ($query) use ($request) {

                $query->where('category_id', $request->category_id);
            })

            ->when($request->brand_id, function ($query) use ($request) {

                $query->where('brand_id', $request->brand_id);
            })

            ->latest()

            ->paginate(10);

        return $this->successResponse(
            'Product list fetched successfully',
            ProductResource::collection($products)
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $imagePath = null;

        if ($request->hasFile('image')) {

            $imagePath = $request->file('image')
                ->store('products', 'public');
        }

        $product = Product::create([

            'category_id' => $request->category_id,

            'brand_id' => $request->brand_id,

            'name' => $request->name,

            'slug' => Str::slug($request->slug),

            'description' => $request->description,

            'price' => $request->price,

            'stock' => $request->stock,

            'sku' => $request->sku,

            'status' => $request->status,

            'image' => $imagePath,
        ]);

        return $this->successResponse(
            'Product created successfully',
            new ProductResource($product)
        );
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load([
            'category',
            'brand'
        ]);

        return $this->successResponse(
            'Product fetched successfully',
            new ProductResource($product)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $imagePath = $product->image;

        if ($request->hasFile('image')) {

            $imagePath = $request->file('image')
                ->store('products', 'public');
        }

        $product->update([

            'category_id' => $request->category_id,

            'brand_id' => $request->brand_id,

            'name' => $request->name,

            'slug' => Str::slug($request->slug),

            'description' => $request->description,

            'price' => $request->price,

            'stock' => $request->stock,

            'sku' => $request->sku,

            'status' => $request->status,

            'image' => $imagePath,
        ]);

        return $this->successResponse(
            'Product updated successfully',
            new ProductResource($product)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return $this->successResponse(
            'Product deleted successfully'
        );
    }
}
