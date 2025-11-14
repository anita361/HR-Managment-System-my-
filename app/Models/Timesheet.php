<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $table = 'timesheets';

    protected $fillable = [
        'project',
        'deadline',
        'total_hours',
        'remaining_hours',
        'date',
        'hours',
        'description',
    ];

    protected $casts = [
        'total_hours' => 'float',
        'remaining_hours' => 'float',
        'hours' => 'float',
        'deadline' => 'date',
        'date' => 'date',
    ];
}
