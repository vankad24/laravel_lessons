<?php
declare(strict_types=1);

namespace App\Listeners\Post;

use App\Events\Post\PostCreatedEvent;
use Illuminate\Support\Facades\Log;

class CreatePostListener
{
    public function handle(PostCreatedEvent $event): void
    {
        $post = $event->post;

        Log::info('CreatePostListener handled a PostCreatedEvent.', ['post_id' => $post->id]);

        if ($post->status === 'published' || $post->status === 'scheduled') {
            $post->moderations()->create(['status' => 'wait']);
            Log::info('Post was published, moderation record created.', ['post_id' => $post->id]);
        }
    }
}
