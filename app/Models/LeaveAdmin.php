<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveAdmin extends Model
{
    use HasFactory;

    protected $table = 'leaves_admins';

    protected $fillable = [
        'user_id', 'start_date', 'end_date', 'leave_type', 'reason', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
