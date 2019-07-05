<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Category;
use App\Http\Requests\Category\StoreCategoryRequest;
use Illuminate\Http\Response;

class CategoriesController extends Controller
{
    public function index(): ResourceCollection
    {
        return JsonResource::collection(
            Category::orderBy('id')->get()
        );
    }
    
    public function show(Category $category): JsonResource
    {
        return new JsonResource($category);
    }
    
    public function store(StoreCategoryRequest $request): JsonResource
    {
        return new JsonResource(
            Category::create($request->validated())
        );
    }

    public function update(StoreCategoryRequest $request, Category $category): JsonResource
    {
        $category->update($request->validated());
        return new JsonResource($category);
    }
    
    public function destroy(Request $request, Category $category): Response
    {
        $category->delete();
        return response()->noContent();
    }
}
