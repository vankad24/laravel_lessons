<?php
declare(strict_types=1);

namespace App\Listeners\Post;

use App\Events\Post\PostUpdatedEvent;
use Illuminate\Support\Facades\Log;

class PostUpdatedListener
{
    public function handle(PostUpdatedEvent $event): void
    {
        Log::info('PostUpdatedListener handled a PostUpdatedEvent.', ['post_id' => $event->post->id]);
    }
}
