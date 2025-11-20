<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
      protected $table = 'assets';
    protected $fillable = [
        'name',
        'asset_id',
        'purchase_date',
        'purchase_from',
        'manufacturer',
        'model',
        'serial_number',
        'supplier',
        'condition',
        'warranty_months',
        'value',
        'asset_user_id',
        'description',
        'status',
    ];

    // If purchase_date is a date column:
    protected $dates = ['purchase_date'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'asset_user_id');
    }
}
