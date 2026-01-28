<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $fillable = [
        'caller_id',
        'receiver_id',
        'type',
        'status',
        'started_at',
        'ended_at',
        'duration'
    ];

    const STATUS_ONGOING = 'ongoing';
    const STATUS_ENDED   = 'ended';
    const STATUS_MISSED  = 'missed';


    public function caller()
    {
        return $this->belongsTo(User::class, 'caller_id');
    }


    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function scopeBetweenUsers($query, $user1, $user2)
    {
        return $query->where(function ($q) use ($user1, $user2) {
            $q->where('caller_id', $user1)
                ->where('receiver_id', $user2);
        })->orWhere(function ($q) use ($user1, $user2) {
            $q->where('caller_id', $user2)
                ->where('receiver_id', $user1);
        });
    }


    public function getFormattedDurationAttribute()
    {
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
