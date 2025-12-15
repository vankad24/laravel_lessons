<?php
declare(strict_types=1);

namespace App\Events\Moderation;

use App\Events\AbstractEvent;
use App\Events\EventType;
use App\Models\Moderation;

class ModerationAcceptedEvent extends AbstractEvent
{
    public function __construct(
        public readonly Moderation $moderation
    ) {
        parent::__construct(EventType::ACCEPT_MODERATION);
    }
}
