<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    public function index(): JsonResponse
    {
        $posts = Post::published()->with(['category', 'tags'])->get();
        return response()->json(
            PostResource::collection($posts),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function store(Request $request): JsonResource
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'status' => ['sometimes', 'string', Rule::in(['scheduled', 'published', 'declined'])],
        ]);

        $post = $request->user()->posts()->create($validated);

        if (isset($validated['tags'])) {
            $post->tags()->attach($validated['tags']);
        }

        if ($post->status === 'published') {
            $post->moderations()->create(['status' => 'wait']);
        }

        return new PostResource($post->load(['category', 'tags']));
    }

    public function show(Post $post): JsonResponse
    {
        $post->increment('views');

        return response()->json(
            new PostResource($post->load(['category', 'tags', 'comments'])),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function update(Request $request, Post $post): JsonResource
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'status' => ['sometimes', 'string', Rule::in(['scheduled', 'published', 'declined'])],
        ]);

        $post->update($validated);

        if (isset($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        if ($post->wasChanged('status') && $post->status === 'published') {
            $post->moderations()->create(['status' => 'wait']);
        }

        return new PostResource($post->load(['category', 'tags']));
    }

    public function destroy(Post $post): Response
    {
        $post->delete();
        return response()->noContent();
    }

    public function like(Post $post): JsonResource
    {
        $post->increment('likes');
        return new PostResource($post);
    }
}
