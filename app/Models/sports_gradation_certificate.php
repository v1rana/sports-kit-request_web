<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class sports_gradation_certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_no', 'sports_person_name', 'aadhaar_no','email','dob','gender', 'mobile_no','otp','expires_at', 'district_sportsperson_belongs', 'domicile_state',
        'plays_for_statte_org', 'name_sports_discipline', 'tournament_name', 'month_year','venue_of_tournament', 
        'organising_authority', 'tournament_type', 'medal_won', 'participation_level','date',
        'aadhaar_card','domicile_certificate','sports_certificate','more_than25_photo','profile_picture','status','approve_reject_datetime','dso_id','certificate_pdf','enquiry_pdf','enquiry_pdf_datetime','replied_pdf','replied_pdf_datetime','type_of_event','terms_conditions'
    ];
}
