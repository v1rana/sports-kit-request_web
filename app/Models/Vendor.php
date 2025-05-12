<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
	'vendor_name',
	'owner_name',
	'pan_of_owner',
	'firm_address',
	'mob',
	'district',
	'pincode',
	'vendor_assigned_document'
	];

    // Many-to-Many Relationship
    public function sports()
{
    return $this->belongsToMany(Sport::class, 'sport_vendor')
                ->withPivot('rate', 'photo')
                ->withTimestamps(); // This tells Laravel to handle timestamps
}
}
