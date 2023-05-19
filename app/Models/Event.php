<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'starts_at',
        'ends_at',
        'type'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, UserEvent::class, 'event_id', 'user_id');
    }

    public function getUsersCountAttribute(): int
    {
        return $this->users()->count();
    }

    public function getIsFinishedAttribute(): bool
    {
        return $this->ends_at < now();
    }
}
