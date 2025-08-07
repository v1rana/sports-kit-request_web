<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationHOSP extends Model
{
    protected $table = 'hosp_education';

    protected $fillable = [
        'user_id',
        'qualification',
        'other_qualification',
        'domicile',
        'certificate_path',
        'application_id'
    ];
}
