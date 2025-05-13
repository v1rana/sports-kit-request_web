<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class DSO extends Authenticatable
{
    use HasFactory;

    protected $table = 'dsos';

    protected $fillable = [
        'name', 'email', 'district', 'status','mob','otp','expires_at'
    ];

    //protected $hidden = ['password'];

    public function sportsRequests()
    {
        return $this->hasMany(SportsKitRequisition::class, 'district', 'district');
    }
}
