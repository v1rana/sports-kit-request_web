<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ADC extends Model
{
    use HasFactory;

    protected $table = 'adcs'; // Custom table name

    protected $fillable = [
        'name',
        'designation',
        'district',
        'mob',
        'status',
        'otp',
        'expires_at'
    ];
}
