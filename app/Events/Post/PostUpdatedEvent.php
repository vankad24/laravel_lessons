<?php
declare(strict_types=1);

namespace App\Events\Post;

use App\Events\AbstractEvent;
use App\Events\EventType;
use App\Models\Post;

class PostUpdatedEvent extends AbstractEvent
{
    public function __construct(
        public readonly Post $post
    ) {
        parent::__construct(EventType::UPDATE_POST);
    }
}
