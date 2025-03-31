<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GetDistricts extends Model
{
    use HasFactory;

    protected $table = 'districts'; // Your table name

    protected $fillable = ['name','state_id','id']; // Define fillable columns
}
