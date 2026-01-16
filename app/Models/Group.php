<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'created_by','avatar'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user')->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(GroupMessage::class);
    }


    // group creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
