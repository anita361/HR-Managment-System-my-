<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // Fillable fields for mass assignment
    protected $fillable = [
        'user_id',
        'type',        // 'group', 'private', or 'other'
        'message_id',  // optional, linked message
        'title',       // notification title
        'body',        // notification message
        'url',         // optional link
        'is_read',     // read status
    ];

    // Casts for proper data types
    protected $casts = [
        'is_read' => 'boolean',
        'message_id' => 'integer',
    ];

    /**
     * The user who receives this notification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * If the notification is related to a message
     */
    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }
}
