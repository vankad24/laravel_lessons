<?php
declare(strict_types=1);

namespace App\Listeners\Post;

use App\Events\Post\PostLikedEvent;
use Illuminate\Support\Facades\Log;

class PostLikedListener
{
    public function handle(PostLikedEvent $event): void
    {
        Log::info('PostLikedListener handled a PostLikedEvent.', ['post_id' => $event->post->id]);
    }
}
