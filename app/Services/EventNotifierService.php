<?php
declare(strict_types=1);

namespace App\Services;

use App\Events\AbstractEvent;
use App\Events\EventType;

// Singleton class
final class EventNotifierService
{
    private static ?self $instance = null;

    /** @var array<string, callable[]> */
    private array $listeners = [];

    private function __construct()
    {
        foreach (EventType::cases() as $case) {
            $this->listeners[$case->value] = [];
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add(EventType|string $eventType, callable $callback): void
    {
        $type = $eventType instanceof EventType
            ? $eventType
            : EventType::from($eventType);

        $this->listeners[$type->value][] = $callback;
    }

    public function delete(EventType|string $eventType, callable $callback): bool
    {
        $type = $eventType instanceof EventType
            ? $eventType
            : EventType::from($eventType);

        foreach ($this->listeners[$type->value] as $i => $listener) {
            if ($listener === $callback) {
                unset($this->listeners[$type->value][$i]);
                return true;
            }
        }
        return false;
    }

    public function makeEvent(AbstractEvent $event): void
    {
        // обычные слушатели
        foreach ($this->listeners[$event->eventType->value] as $callback) {
            if ($callback($event) === true) {
                return;
            }
        }

        // broadcast
        foreach ($this->listeners[EventType::BROADCAST->value] as $callback) {
            if ($callback($event) === true) {
                return;
            }
        }
    }
}
