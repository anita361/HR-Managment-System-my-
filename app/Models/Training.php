<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_type',
        'trainer_id',
        'employees_id',
        'training_cost',
        'start_date',
        'end_date',
        'description',
        'status',
    ];

    protected $casts = [
        'employees_id' => 'array',
    ];


    public function employeeModels()
    {
        return Employee::whereIn('id', $this->employees_id ?? [])->get();
    }
}
