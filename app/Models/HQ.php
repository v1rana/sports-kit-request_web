<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HQ extends Model {
    use HasFactory;

    protected $table = 'hqs'; // Custom table name

    protected $fillable = [
        'hq_name',
        'mob',
        'status',
        'otp',
        'expires_at'
    ];

    public function sportsRequests() {
        return $this->hasMany(HQSportsRequest::class, 'hq_id');
    }
}
