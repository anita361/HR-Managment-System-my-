<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollOvertime extends Model
{
    use HasFactory;

    
    protected $table = 'payrollovertimes';

    
    protected $fillable = [
        'name',
        'rate_type',
        'rate',
    ];
}

