<?php
declare(strict_types=1);

namespace App\Events\Moderation;

use App\Models\Moderation;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModerationDeclinedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Moderation $moderation
    ) {
    }
}
