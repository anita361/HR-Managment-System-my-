<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'org_password',
        'status',
        'avatar',
        'role_name',
        'last_seen',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen' => 'datetime',
    ];

   

    public function employee()
    {
        return $this->hasOne(Employee::class, 'employee_id', 'user_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user')
            ->withTimestamps();
    }

    public function groupMessages()
    {
        return $this->hasMany(GroupMessage::class, 'sender_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

   

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $maxId = self::selectRaw('MAX(CAST(SUBSTRING(user_id, 4) AS UNSIGNED)) as max_id')
                ->value('max_id');

            $nextID = $maxId ? $maxId + 1 : 1;
            $model->user_id = 'KH-' . sprintf("%04d", $nextID);
        });
    }

    

    public function isOnline()
    {
        return $this->last_seen && $this->last_seen->gt(now()->subMinutes(5));
    }

    public function avatarUrl()
    {
        return $this->avatar
            ? asset('uploads/avatars/' . $this->avatar)
            : asset('images/default-avatar.png');
    }

   

    public function saveNewuser(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'role_name' => 'required|string|max:255',
            'password'  => 'required|string|confirmed',
        ]);

        try {
            $save = new self();
            $save->name       = $request->name;
            $save->avatar     = $request->image;
            $save->email      = $request->email;
            $save->join_date  = Carbon::now()->toDayDateTimeString();
            $save->role_name  = $request->role_name;
            $save->status     = 'Active';
            $save->password   = Hash::make($request->password);
            $save->save();

            flash()->success('Account created successfully 🙂');
            return redirect('login');
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed to Create Account. Please try again.');
            return redirect()->back();
        }
    }
}
