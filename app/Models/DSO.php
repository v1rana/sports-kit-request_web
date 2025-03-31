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
        'name', 'email', 'password', 'district', 'status'
    ];

    protected $hidden = ['password'];

    public function sportsRequests()
    {
        return $this->hasMany(SportsKitRequisition::class, 'district', 'district');
    }
}
