<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeclarationsHosp extends Model
{
    protected $table = 'hosp_declarations';


    protected $fillable = ['user_id','declaration_id','declaration_file'];

    public function declaration()
    {
        return $this->belongsTo(Declaration::class);
    }
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
