<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    

    protected $fillable = [
        'name', 'category', 'unit_calculation', 'unit_amount', 'assignee', 'employee_id'
    ];

}
