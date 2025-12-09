<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timesheet extends Model
{
    protected $table = 'timesheets';

    protected $fillable = [
        'employee_id',
        'project',
        'deadline',
        'total_hours',
        'remaining_hours',
        'date',
        'hours',
        'description',
    ];

    protected $casts = [
        'total_hours'     => 'float',
        'remaining_hours' => 'float',
        'hours'           => 'float',
        'deadline'        => 'date',
        'date'            => 'date',
    ];


    public function employee(): BelongsTo
    {

        return $this->belongsTo(\App\Models\Employee::class, 'employee_id', 'id');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}
