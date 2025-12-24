<?php
declare(strict_types=1);

namespace App\Listeners\Moderation;

use App\Events\Moderation\ModerationAcceptedEvent;
use Illuminate\Support\Facades\Log;

class ModerationAcceptedListener
{
    public function handle(ModerationAcceptedEvent $event): void
    {
        Log::info('ModerationAcceptedListener handled a ModerationAcceptedEvent.', ['moderation_id' => $event->moderation->id]);
    }
}
