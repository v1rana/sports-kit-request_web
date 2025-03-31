<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    use HasFactory;

    protected $fillable = ['sports_name'];

    // Many-to-Many Relationship
    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'sport_vendor');
    }
}
