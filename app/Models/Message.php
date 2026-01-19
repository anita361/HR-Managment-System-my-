<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'group_id',
        'sender_id',
        'receiver_id',
        'body',
        'file',
        'is_seen',
        'seen_at',
        'is_deleted',
        'deleted_for',
        'deleted_at',
        'edited_at'
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'deleted_for' => 'array',
        'deleted_at' => 'datetime',
        'edited_at' => 'datetime',
    ];


    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
