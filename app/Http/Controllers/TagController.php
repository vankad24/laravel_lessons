<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            TagResource::collection(Tag::all()),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function show(Tag $tag): JsonResponse
    {
        return response()->json(
            new TagResource($tag->load('posts')),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}
