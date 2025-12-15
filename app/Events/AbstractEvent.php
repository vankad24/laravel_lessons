<?php
declare(strict_types=1);

namespace App\Events;

abstract class AbstractEvent
{
    public function __construct(
        public readonly EventType $eventType
    ) {}
}
