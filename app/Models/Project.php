<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // Table name (optional — only needed if your table name isn't "projects")
    protected $table = 'projects';

    // Columns that can be mass-assigned
    protected $fillable = [
        'project_name',
        'client_name',
        'start_date',
        'end_date',
        'status',
        'description',
    ];

    /**
     * Example relationship: one project has many timesheets
     */
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }
}
