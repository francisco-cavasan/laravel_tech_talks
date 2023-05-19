<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
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

    public function users()
    {
        return $this->belongsToMany(User::class, UserEvent::class, 'event_id', 'user_id');
    }

    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }

    public function getIsFinishedAttribute()
    {
        return $this->ends_at < now();
    }
}
