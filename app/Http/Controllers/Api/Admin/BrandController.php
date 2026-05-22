<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandResource;

class BrandController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $brands = Brand::latest()

            ->paginate(10);

        return $this->successResponse(
            'Brands fetched successfully',
            BrandResource::collection($brands)
        );
    }

    public function store(BrandRequest $request)
    {
        $brand = Brand::create([

            'name' => $request->name,

            'slug' => Str::slug($request->name),
            'status' => $request->status,
        ]);

        return $this->successResponse(
            'Brand created successfully',
            new BrandResource($brand)
        );
    }

    public function show(Brand $brand)
    {
        return $this->successResponse(
            'Brand fetched successfully',
            new BrandResource($brand)
        );
    }

    public function update(
        BrandRequest $request,
        Brand $brand
    ) {

        $brand->update([

            'name' => $request->name,

            'slug' => Str::slug($request->name),

            'status' => $request->status,
        ]);

        return $this->successResponse(
            'Brand updated successfully',
            new BrandResource($brand)
        );
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return $this->successResponse(
            'Brand deleted successfully'
        );
    }
}
