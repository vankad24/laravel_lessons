<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{
    public function index(): JsonResource
    {
        return CategoryResource::collection(Category::all());
    }

    public function show(Category $category): JsonResource
    {
        return new CategoryResource($category->load('posts'));
    }
}
