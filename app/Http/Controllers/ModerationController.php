<?php

namespace App\Http\Controllers;

use App\Events\Moderation\ModerationAcceptedEvent;
use App\Events\Moderation\ModerationDeclinedEvent;
use App\Models\Moderation;
use App\Models\Post;
use App\Models\Comment;
use App\Services\EventNotifierService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ModerationController extends Controller
{
    private EventNotifierService $eventNotifierService;

    public function __construct(EventNotifierService $eventNotifierService)
    {
        $this->middleware(function ($request, $next) {
            if (!in_array($request->user()->role, ['admin', 'moderator'])) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
        $this->eventNotifierService = $eventNotifierService;
    }

    public function indexPosts(): JsonResponse
    {
        $moderations = Moderation::where('moderatable_type', Post::class)
            ->where('status', 'wait')
            ->with('moderatable')
            ->get();

        return response()->json($moderations);
    }

    public function indexComments(): JsonResponse
    {
        $moderations = Moderation::where('moderatable_type', Comment::class)
            ->where('status', 'wait')
            ->with('moderatable')
            ->get();

        return response()->json($moderations);
    }

    public function decline(Request $request, Moderation $moderation): JsonResponse
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // Update the moderation record
        $moderation->status = 'declined';
        $moderation->comment = $validated['comment'];
        $moderation->moderated_by = $request->user()->id;
        $moderation->moderated_at = now();
        $moderation->save();

        // Update the related model's status
        $moderatable = $moderation->moderatable;
        if ($moderatable) {
            $moderatable->status = 'declined'; // Assuming the model has a 'status' attribute
            $moderatable->save();
        }

        $this->eventNotifierService->makeEvent(new ModerationDeclinedEvent($moderation));

        return response()->json($moderation);
    }

    public function accept(Request $request, Moderation $moderation): JsonResponse
    {
        $validated = $request->validate([
            'comment' => 'nullable|string|max:1000',
        ]);

        // Update the moderation record
        $moderation->status = 'accepted';
        $moderation->comment = $validated['comment'] ?? null;
        $moderation->moderated_by = $request->user()->id;
        $moderation->moderated_at = now();
        $moderation->save();

        $this->eventNotifierService->makeEvent(new ModerationAcceptedEvent($moderation));

        // The related model's status should already be 'published' or the desired state
        // upon creation of the moderation record, so no change here.

        return response()->json($moderation);
    }
}