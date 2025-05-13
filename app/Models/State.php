<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;

    protected $table = 'state_names'; // Your table name

    protected $fillable = ['name','id']; // Define fillable columns
}
