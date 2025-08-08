<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $fillable = [
        'user_id',
        'family_id',
        'full_name_en',
        'full_name_hi',
        'father_name_en',
        'father_name_hi',
        'mother_name_en',
        'mother_name_hi',
        'date_of_birth',
        'age',
        'gender',
        'marital_status',
        'district',
        'block_town',
        'ward_village',
        'pincode',
        'email_id',
        'benchmark_disability',
        'caste_category',
        'highest_qualification',
        'current_engagement',
        'annual_income',
        'income_verified',
        'domicile',
        'domicile_doc',
        'played_national_level',
        'organisation_represented',
        'national_level_doc',
        'organisation_doc',
        'status'
        // don't include application_id in fillable since it will be generated
    ];
    protected static function boot()
    {
        parent::boot();

        static::created(function ($application) {
            $application->application_id = 'HOSP' . str_pad($application->id, 6, '0', STR_PAD_LEFT);
            $application->saveQuietly(); // avoid triggering another event
        });
    }

// Optional: Inverse Relationship in UserDetails
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sportsDisciplineHosp()
    {
        return $this->hasOne(SportsDisciplineHosp::class, 'application_id', 'application_id');
    }

    public function educationHosp()
    {
        return $this->hasMany(EducationHOSP::class, 'application_id', 'application_id');
    }

    public function declarationsHosp()
    {
        return $this->hasMany(DeclarationsHosp::class, 'application_id', 'application_id');
    }
}
