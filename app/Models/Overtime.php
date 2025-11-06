<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    protected $table = 'overtimes';

    protected $fillable = [
        'employee_id',
        'ot_date',
        'ot_hours',
        'ot_type',
        'description',
        'status',
        'approved_by',
    ];

    /**
     * The employee who performed the overtime.
     */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class, 'employee_id');
    }

    /**
     * The user who approved this overtime.
     */
    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }
}
