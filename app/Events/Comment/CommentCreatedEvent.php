<?php
declare(strict_types=1);

namespace App\Events\Comment;

use App\Models\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentCreatedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Comment $comment
    ) {
    }
}
