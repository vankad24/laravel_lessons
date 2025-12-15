<?php
declare(strict_types=1);

namespace App\Events;

enum EventType: string
{
    case BROADCAST = 'BROADCAST';
    case CREATE_POST = 'CREATE_POST';
    case UPDATE_POST = 'UPDATE_POST';
    case DELETE_POST = 'DELETE_POST';
    case LIKE_POST = 'LIKE_POST';
    case ACCEPT_MODERATION = 'ACCEPT_MODERATION';
    case DECLINE_MODERATION = 'DECLINE_MODERATION';
}
