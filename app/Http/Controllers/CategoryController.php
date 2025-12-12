<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            CategoryResource::collection(Category::all()),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json(
            new CategoryResource($category->load('posts')),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}
