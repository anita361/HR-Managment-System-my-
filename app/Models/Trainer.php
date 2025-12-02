<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = [
        'trainer_id',
        'full_name',
        'role',
        'email',
        'phone',
        'status',
        'description'
    ];
}
