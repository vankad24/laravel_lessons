<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string|in:post,user', // Add other types as needed
        ]);

        $modelMap = [
            'post' => \App\Models\Post::class,
            'user' => \App\Models\User::class,
        ];

        $modelClass = $modelMap[$validated['commentable_type']];
        $commentable = $modelClass::findOrFail($validated['commentable_id']);

        $comment = $commentable->comments()->create([
            'body' => $validated['body'],
            'user_id' => $request->user()->id,
        ]);

        return new CommentResource($comment->load('user'));
    }
}
