<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees'; // Specify the table name if it's not pluralized
    
<<<<<<< HEAD
    protected $fillable = [
=======
    protected $fillable = [ // Using Models
>>>>>>> 666be08a4b014c268fe5f6cc17e3d71fb9da67d7
        'employee_id',
        'name',
        'email',
        'birth_date',
        'gender',
        'line_manager' 
    ];
}
  