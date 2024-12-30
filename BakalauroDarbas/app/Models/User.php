<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table ="users";

    const ROLE_USER = 'user';
    const ROLE_DOCTOR = 'doctor';
    const ROLE_ADMIN = 'admin';

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isDoctor()
    {
        return $this->role === self::ROLE_DOCTOR;
    }

    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'password',
        'profile_photo',
        'role',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo ? $this->profile_photo : asset('img/profileuser.png');
    }
    
    public function rehabilitationPlans()
    {
        return $this->hasMany(RehabilitationPlan::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'user_id');
    }
    
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'from_user_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'to_user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'from_user_id')
            ->orWhere('to_user_id', $this->id);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function savedPlans()
    {
        return $this->belongsToMany(RehabilitationPlan::class, 'saved_plans', 'user_id', 'plan_id');
    }

}
