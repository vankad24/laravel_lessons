<?php
declare(strict_types=1);

namespace App\Events;

enum EventType: string
{
    case BROADCAST = 'BROADCAST';
    case CREATE_POST = 'CREATE_POST';
    case DELETE_POST = 'DELETE_POST';
}
