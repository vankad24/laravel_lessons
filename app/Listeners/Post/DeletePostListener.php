<?php
declare(strict_types=1);

namespace App\Listeners\Post;

use App\Events\Post\PostDeletedEvent;
use Illuminate\Support\Facades\Log;

class DeletePostListener
{
    public function handle(PostDeletedEvent $event): void
    {
        Log::info('DeletePostListener handled a PostDeletedEvent.', ['post_id' => $event->post->id]);
        // Future logic for when a post is deleted, e.g., sending notifications.
    }
}
