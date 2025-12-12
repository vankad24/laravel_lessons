<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagController extends Controller
{
    public function index(): JsonResource
    {
        return TagResource::collection(Tag::all());
    }

    public function show(Tag $tag): JsonResource
    {
        return new TagResource($tag->load('posts'));
    }
}
