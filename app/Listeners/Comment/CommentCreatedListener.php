<?php
declare(strict_types=1);

namespace App\Listeners\Comment;

use App\Events\Comment\CommentCreatedEvent;
use Illuminate\Support\Facades\Log;

class CommentCreatedListener
{
    public function handle(CommentCreatedEvent $event): void
    {
        $comment = $event->comment;
        Log::info('CommentCreatedListener handled a CommentCreatedEvent.', ['comment_id' => $comment->id]);

        // Create a moderation record for the new comment
        $comment->moderations()->create(['status' => 'wait']);
        Log::info('Moderation record created for new comment.', ['comment_id' => $comment->id]);
    }
}
