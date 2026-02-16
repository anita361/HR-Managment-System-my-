<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    protected $fillable = [
        'group_id',
        'sender_id',
        'body',
        'is_deleted',
        'deleted_for',
        'edited_at',
        'file_path',
        'file_type',
        'file_name',
    ];
    protected $casts = [
        'is_deleted' => 'boolean',
        'deleted_for' => 'array',
        'edited_at' => 'datetime',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function userDeletes()
    {
        return $this->hasMany(MessageUserDelete::class, 'message_id');
    }


    public function isDeletedFor($userId)
    {
        if ($this->is_deleted) return true;

        $deletedFor = [];
        if ($this->deleted_for) {
            if (is_array($this->deleted_for)) {
                $deletedFor = $this->deleted_for;
            } else {
                $deletedFor = json_decode($this->deleted_for, true);
            }
        }

        return in_array($userId, $deletedFor);
    }
}
