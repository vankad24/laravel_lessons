<?php
declare(strict_types=1);

namespace App\Listeners\Moderation;

use App\Events\Moderation\ModerationDeclinedEvent;
use Illuminate\Support\Facades\Log;

class ModerationDeclinedListener
{
    public function handle(ModerationDeclinedEvent $event): void
    {
        $post = $event->post;
        $post->status = 'declined';
        $post->save();
        Log::info('ModerationDeclinedListener handled a ModerationDeclinedEvent.', ['moderation_id' => $event->moderation->id, 'post_id' => $post->id]);
    }
}
