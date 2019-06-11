<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class CategoriesController extends Controller
{
    public function index(): ResourceCollection
    {
        return JsonResource::collection(
            Category::orderBy('id')->get()
        );
    }
    
    public function store(CategoryRequest $request): JsonResource
    {
        return new JsonResource(
            Category::create($request->validated())
        );
    }
}
