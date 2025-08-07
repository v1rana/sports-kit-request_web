<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'member_id',
        'password',
        'status',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userDetails()
    {
        return $this->hasMany(UserDetails::class, 'user_id');
    }
    public function applicationDetails()
    {
        return $this->hasOne(UserDetails::class, 'user_id');
    }
   
    public function sportsDisciplineHosp()
    {
        return $this->hasOne(SportsDisciplineHosp::class, 'user_id');
    }
    public function educationHosp()
    {
        return $this->hasMany(EducationHOSP::class, 'user_id');
    }
    public function declarationsHosp()
    {
        return $this->hasOne(DeclarationsHosp::class, 'user_id');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // Helper method (optional)
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }
}
