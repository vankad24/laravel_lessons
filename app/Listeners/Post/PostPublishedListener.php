<?php
declare(strict_types=1);

namespace App\Listeners\Post;

use App\Events\Post\PostPublishedEvent;
use Illuminate\Support\Facades\Log;

class PostPublishedListener
{
    public function handle(PostPublishedEvent $event): void
    {
        $post = $event->post;
        Log::info('PostPublishedListener handled a PostPublishedEvent.', ['post_id' => $post->id]);

        // Create a moderation record for the published post
        $post->moderations()->create(['status' => 'wait']);
        Log::info('Moderation record created for published post.', ['post_id' => $post->id]);
    }
}
