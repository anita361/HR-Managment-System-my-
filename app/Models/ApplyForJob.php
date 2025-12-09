<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplyForJob extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_title',
        'phone',
        'name',
        'email',
        'message',
        'cv_upload',
    ];

     public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
