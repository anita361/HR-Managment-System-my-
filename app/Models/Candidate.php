<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $table = 'candidates'; 

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'employee_id',
        'created_date', 
        'phone',
    ];

   
    protected $casts = [
        'created_date' => 'date',
    ];

     public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
