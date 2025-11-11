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
        'min_start_time' => 'string',
        'start_time' => 'string',
        'max_start_time' => 'string',
        'min_end_time' => 'string',
        'end_time' => 'string',
        'max_end_time' => 'string',
    ];

    public function employee()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }

    public function shift()
    {
        return $this->belongsTo(\App\Models\Shift::class, 'shift_id');
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class, 'department_id');
    }
}
