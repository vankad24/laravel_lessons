<?php
declare(strict_types=1);

namespace App\Listeners\Moderation;

use App\Events\Moderation\ModerationDeclinedEvent;
use Illuminate\Support\Facades\Log;

class ModerationDeclinedListener
{
    public function handle(ModerationDeclinedEvent $event): void
    {
        Log::info('ModerationDeclinedListener handled a ModerationDeclinedEvent.', ['moderation_id' => $event->moderation->id]);
    }
}
