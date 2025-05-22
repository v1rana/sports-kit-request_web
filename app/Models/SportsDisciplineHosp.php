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
        'event_type',
        'tournament_id',
        'game_id',
        'organizing_committee',
        'tournament_level',
        'represented_india',
        'achievement_date',
        'tournament_venue',
        'medal_won',
        'match_played_by_team',
        'match_played_by_me',
        'osp_achivement_certificate_path',
        'international_achievement_Verification_certificate_path',
    ];
	
	public function tournament()
	{
		return $this->belongsTo(Schedule12::class, 'tournament_id');
	}
	public function game()
    {
        return $this->belongsTo(GameHosp::class, 'game_id');
    }
}
