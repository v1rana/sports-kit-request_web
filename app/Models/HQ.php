<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HQ extends Model {
    use HasFactory;

    protected $fillable = ['hq_name', 'status'];

    public function sportsRequests() {
        return $this->hasMany(HQSportsRequest::class, 'hq_id');
    }
}
