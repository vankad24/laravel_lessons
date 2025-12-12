<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function index(): JsonResource
    {
        $posts = Post::published()->with(['category', 'tags'])->get();
        return PostResource::collection($posts);
    }

    public function store(Request $request): JsonResource
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'is_published' => 'sometimes|boolean',
        ]);

        $post = Post::create($validated);

        if (isset($validated['tags'])) {
            $post->tags()->attach($validated['tags']);
        }

        return new PostResource($post->load(['category', 'tags']));
    }

    public function show(Post $post): JsonResource
    {
        return new PostResource($post->load(['category', 'tags', 'comments']));
    }

    public function update(Request $request, Post $post): JsonResource
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'is_published' => 'sometimes|boolean',
        ]);

        $post->update($validated);

        if (isset($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return new PostResource($post->load(['category', 'tags']));
    }

    public function destroy(Post $post): Response
    {
        $post->delete();
        return response()->noContent();
    }
}
