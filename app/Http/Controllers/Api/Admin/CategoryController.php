<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $categories = Category::query()

            ->when($request->search, function ($query) use ($request) {

                $query->where('name', 'LIKE', '%' . $request->search . '%');
            })

            ->latest()

            ->paginate(10);

        return $this->successResponse(
            'Category list fetched successfully',
            CategoryResource::collection($categories)
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'status' => $request->status,
        ]);

        return $this->successResponse(
            'Category created successfully',
            new CategoryResource($category)
        );
    }


    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->successResponse(
            'Category fetched successfully',
            new CategoryResource($category)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'status' => $request->status,
        ]);

        return $this->successResponse(
            'Category updated successfully',
            new CategoryResource($category)
        );
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->successResponse(
            'Category deleted successfully'
        );
    }
}
