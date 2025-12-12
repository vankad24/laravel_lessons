<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse; // Добавлено

class PostController extends Controller
{
    public function index(): JsonResponse // Изменен тип возвращаемого значения
    {
        $posts = Post::published()->with(['category', 'tags'])->get();
        return response()->json( // Явный возврат JsonResponse
            PostResource::collection($posts),
            200, // HTTP статус
            [],  // Заголовки
            JSON_UNESCAPED_UNICODE // Флаг для json_encode
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
            'is_published' => 'sometimes|boolean',
        ]);

        $post = Post::create($validated);

        if (isset($validated['tags'])) {
            $post->tags()->attach($validated['tags']);
        }

        return new PostResource($post->load(['category', 'tags']));
    }

    public function show(Post $post): JsonResponse
    {
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
