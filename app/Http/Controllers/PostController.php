<?php

namespace App\Http\Controllers;

use App\Events\Post\PostCreatedEvent;
use App\Events\Post\PostDeletedEvent;
use App\Events\Post\PostLikedEvent;
use App\Events\Post\PostPublishedEvent;
use App\Events\Post\PostUpdatedEvent;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
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
            'published_at' => 'nullable|date',
        ]);

        $post = $request->user()->posts()->create($validated);

        if (!empty($validated['tags'])) {
            $post->tags()->attach($validated['tags']);
        }

        event(new PostCreatedEvent($post));

        if ($post->status === 'published') {
            event(new PostPublishedEvent($post));
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
            'published_at' => 'nullable|date',
        ]);

        $post->update($validated);

        if (array_key_exists('tags', $validated)) {
            $post->tags()->sync($validated['tags'] ?? []);
        }

        event(new PostUpdatedEvent($post));

        if ($post->wasChanged('status') && $post->status === 'published') {
            event(new PostPublishedEvent($post));
        }

        return new PostResource($post->load(['category', 'tags']));
    }

    public function destroy(Post $post): Response
    {
        $post_copy = clone $post;
        $post->delete();

        event(new PostDeletedEvent($post_copy));

        return response()->noContent();
    }

    public function like(Request $request, Post $post): JsonResource
    {
        $request->user()->likedPosts()->toggle($post);

        $post->likes = $post->likers()->count();
        $post->save();

        event(new PostLikedEvent($post));

        return new PostResource($post);
    }
}
