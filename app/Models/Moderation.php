<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Moderation extends Model
{
    use HasFactory;

    protected $fillable = [
        'moderatable_id',
        'moderatable_type',
        'status',
        'comment',
        'moderated_by',
        'moderated_at',
    ];

    protected $casts = [
        'moderated_at' => 'datetime',
    ];

    public function moderatable(): MorphTo
    {
        return $this->morphTo();
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }
}
