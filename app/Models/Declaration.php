<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Declaration extends Model
{
    protected $fillable = ['point_text'];

    // public function hospDeclarations()
    // {
    //     return $this->hasMany(DeclarationsHosp::class);
    // }
}
