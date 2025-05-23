<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporarySportsKitRequisition extends Model
{
    use HasFactory;

    protected $table = 'temporary_sports_kit_requisitions';

    protected $fillable = [
        'applicant_id',
        'user_id',
        'name',
        'district',
        'block',
        'area_name',
        'designation',
        'specific_designation',
        'sports_equipment',
    ];

    protected $casts = [
        'sports_equipment' => 'array',
    ];
}
