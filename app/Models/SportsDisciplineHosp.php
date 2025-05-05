<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportsDisciplineHosp extends Model
{
    protected $table = 'hosp_sports_discipline';

    protected $fillable = [
        'user_id',
        'physical_disability',
        'disability_type_id',
        'disability_doc',
        'tournament_id',
        'game_id',
        'organizing_committee',
        'tournament_level',
        'represented_india',
        'achievement_date',
        'tournament_venue',
        'medal_won',
        'participation_level',
        'certificate_path',
    ];
}
