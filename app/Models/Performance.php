<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
   protected $fillable = [
    'name',
    'department',
    'designation',
    'qualification',
    'emp_id',
    'date_of_join',
    'date_of_confirmation',
    'previous_experience',
    'ro_name',
    'ro_designation',
];
}
