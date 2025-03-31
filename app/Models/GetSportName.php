<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GetSportName extends Model
{
    use HasFactory;

    protected $table = 'sport_names'; // Your table name

    protected $fillable = ['name','id']; // Define fillable columns
}
