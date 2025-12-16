<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikedPostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $likedPosts = $user->likedPosts()
            ->with(['user', 'category'])
            ->latest('post_user_likes.created_at')
            ->paginate(10);

        return view('liked', ['posts' => $likedPosts]);
    }
}