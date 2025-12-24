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
    }
}
