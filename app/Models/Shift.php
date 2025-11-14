<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shifts';

    protected $fillable = [
        'name',
        'min_start_time',
        'start_time',
        'max_start_time',
        'min_end_time',
        'end_time',
        'max_end_time',
        'break_time_minutes',
        'recurring',
        'repeat_every',
        'days',
        'end_on',
        'indefinite',
        'tag',
        'note'
    ];

    protected $casts = [
        'days' => 'array',
        'recurring' => 'integer',
        'indefinite' => 'integer',
        'break_time_minutes' => 'integer',
    ];

    /**
     * Accessor: returns start_time as "H:i" (e.g. "09:30") or null.
     * Use: $shift->start_time_hm
     */
    public function getStartTimeHmAttribute()
    {
        if (empty($this->start_time)) {
            return null;
        }

        try {
            return Carbon::parse($this->start_time)->format('H:i');
        } catch (\Exception $e) {
            return $this->start_time; // fallback to raw value if parsing fails
        }
    }

    /**
     * Accessor: returns end_time as "H:i" (e.g. "17:00") or null.
     * Use: $shift->end_time_hm
     */
    public function getEndTimeHmAttribute()
    {
        if (empty($this->end_time)) {
            return null;
        }

        try {
            return Carbon::parse($this->end_time)->format('H:i');
        } catch (\Exception $e) {
            return $this->end_time;
        }
    }

    /**
     * Accessor: formatted end_on date (Y-m-d). Use: $shift->end_on_formatted
     */
    public function getEndOnFormattedAttribute()
    {
        return $this->end_on ? $this->end_on->format('Y-m-d') : null;
    }
}
