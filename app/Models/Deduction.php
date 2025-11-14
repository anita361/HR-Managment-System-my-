<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;

    protected $table = 'deductions';

   protected $fillable = [
        'name',
        'unit_calculation',
        'unit_amount',
        'assignee',
        'employee_id',
    ];

    protected $casts = [
        'unit_calculation' => 'boolean',
        'unit_amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
