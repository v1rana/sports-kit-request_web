<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_name'];

    // Many-to-Many Relationship
    public function sports()
    {
        return $this->belongsToMany(Sport::class, 'sport_vendor');
    }
}
