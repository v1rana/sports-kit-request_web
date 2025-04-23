<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventHosp extends Model
{
    protected $table = 'hosp_events';


    protected $fillable = [
        'user_id',
        'event_type',
        'aadhaar',
        'tournament_id',
        'domicile',
        'played_national_level',
        'organisation_represented',
        'domicile_certificate',
        'national_certificate',
        'org_certificate',
    ];
}
