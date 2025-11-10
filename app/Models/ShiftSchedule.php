<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftSchedule extends Model
{
    protected $table = 'shift_schedules';

    protected $fillable = [
        'department_id',
        'employee_id',
        'date',
        'shift_id',
        'min_start_time',
        'start_time',
        'max_start_time',
        'min_end_time',
        'end_time',
        'max_end_time',
        'break_time',
        'accept_extra_hours',
        'publish'
    ];


    protected $casts = [
        'accept_extra_hours' => 'boolean',
        'publish' => 'boolean',
        'date' => 'date',
    ];
}
