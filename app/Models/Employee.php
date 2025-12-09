<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees'; 

    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'birth_date',
        'gender',
        'line_manager'
    ];

    public function modulePermissions()
    {
        return $this->hasMany(module_permission::class, 'employee_id', 'employee_id');
    }

     public function user()
    {
        return $this->belongsTo(User::class, 'employee_id', 'user_id');
    }
}
