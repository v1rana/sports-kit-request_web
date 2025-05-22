<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameHosp extends Model
{
    protected $table = 'games';
	
	protected $fillable = ['name','is_para']; 
}
