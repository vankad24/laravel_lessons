<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfilePageController extends Controller
{
    public function show(User $user)
    {
        $user->load([
            'posts' => function ($query) {
                $query->published()->latest();
            },
            'posts.user', 'posts.category', 'posts.comments.user',
            'comments' => function ($query) {
                $query->latest();
            },
            'comments.user'
        ]);

        return view('profile', ['user' => $user]);
    }
}