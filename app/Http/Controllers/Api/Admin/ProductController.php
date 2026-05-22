<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponseTrait;

    public function index(Request $request)
    {
        // $products = Product::with([

        //     'category',

        //     'brand',

        //     'images'
        // ])

        //     ->when(
        //         $request->search,
        //         function ($query) use ($request) {

        //             $query->where(
        //                 'name',
        //                 'LIKE',
        //                 '%' . $request->search . '%'
        //             );
        //         }
        //     )

        //     ->when(
        //         $request->category_id,
        //         function ($query) use ($request) {

        //             $query->where(
        //                 'category_id',
        //                 $request->category_id
        //             );
        //         }
        //     )

        //     ->when(
        //         $request->brand_id,
        //         function ($query) use ($request) {

        //             $query->where(
        //                 'brand_id',
        //                 $request->brand_id
        //             );
        //         }
        //     )
        //     ->when(
        //         $request->min_price,
        //         function ($query) use ($request) {

        //             $query->where(
        //                 'price',
        //                 '>=',
        //                 $request->min_price
        //             );
        //         }
        //     )

        //     ->when(
        //         $request->max_price,
        //         function ($query) use ($request) {

        //             $query->where(
        //                 'price',
        //                 '<=',
        //                 $request->max_price
        //             );
        //         }
        //     )

        //     ->when(
        //         $request->rating,
        //         function ($query) use ($request) {

        //             $query->where(
        //                 'average_rating',
        //                 '>=',
        //                 $request->rating
        //             );
        //         }
        //     )
        //     ->when(
        //         $request->sort,
        //         function ($query) use ($request) {

        //             switch ($request->sort) {

        //                 case 'latest':

        //                     $query->latest();

        //                     break;

        //                 case 'oldest':

        //                     $query->oldest();

        //                     break;

        //                 case 'price_low':

        //                     $query->orderBy(
        //                         'price',
        //                         'asc'
        //                     );

        //                     break;

        //                 case 'price_high':

        //                     $query->orderBy(
        //                         'price',
        //                         'desc'
        //                     );

        //                     break;

        //                 case 'rating':

        //                     $query->orderBy(
        //                         'average_rating',
        //                         'desc'
        //                     );

        //                     break;

        //                 default:

        //                     $query->latest();

        //                     break;
        //             }
        //         }
        //     )

        //     ->paginate(10);

        $products = Cache::remember(

            'products_list',

            600,

            function () use ($request) {

                return Product::with([

                    'category',

                    'brand',

                    'images'
                ])

                    ->when(
                        $request->search,
                        function ($query) use ($request) {

                            $query->where(
                                'name',
                                'LIKE',
                                '%' . $request->search . '%'
                            );
                        }
                    )

                    ->when(
                        $request->category_id,
                        function ($query) use ($request) {

                            $query->where(
                                'category_id',
                                $request->category_id
                            );
                        }
                    )

                    ->when(
                        $request->brand_id,
                        function ($query) use ($request) {

                            $query->where(
                                'brand_id',
                                $request->brand_id
                            );
                        }
                    )

                    ->latest()

                    ->paginate(10);
            }
        );

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
        Cache::forget('products_list');
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

        Cache::forget('products_list');
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
        Cache::forget('products_list');
        return $this->successResponse(
            'Product deleted successfully'
        );
    }
}
