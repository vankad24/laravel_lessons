<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'user_id'];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function moderations(): MorphMany
    {
        return $this->morphMany(Moderation::class, 'moderatable');
    }
}
