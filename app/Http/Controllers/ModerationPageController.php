<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModerationPageController extends Controller
{
    public function __construct()
    {
        // Protect the entire controller for moderators/admins
        $this->middleware(function ($request, $next) {
            if (!in_array($request->user()->role, ['admin', 'moderator'])) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function __invoke(Request $request)
    {
        return view('moderation');
    }
}