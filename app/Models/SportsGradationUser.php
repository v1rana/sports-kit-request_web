<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SportsGradationUser extends Model
{
    use HasFactory;

    protected $table = 'sports_gradation_users';

    protected $fillable = [
        'sports_person_name',
        'dob',
        'gender',
        'email',
        'mobile_no',
        'otp',
        'expires_at',
        'status',
    ];
}
