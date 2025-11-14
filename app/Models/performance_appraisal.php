<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class performance_appraisal extends Model
{
  protected $fillable = [
    'designation', 'customer_experience', 'marketing', 'management',
    'administration', 'presentation_skill', 'quality_of_work', 'efficiency',
    'integrity', 'professionalism', 'team_work', 'critical_thinking',
    'conflict_management', 'attendance', 'ability_to_meet_deadline', 'status'
];
}
