<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceIndicator extends Model
{
    protected $table = 'performance_indicators'; 

    
    protected $fillable = [
        'designation',
        'customer_eperience',
        'marketing',
        'management',
        'administration',
        'presentation_skill',
        'quality_of_Work',
        'efficiency',
        'integrity',
        'professionalism',
        'team_work',
        'critical_thinking',
        'conflict_management',
        'attendance',
        'ability_to_meet_deadline',
        'status',
    ];

    
}
