<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportsKitRequisition extends Model {
    use HasFactory;

    protected $table = 'sports_kit_requisitions';

    protected $fillable = [
        'applicant_id', 
        'district', 
        'block', 
        'area_name',
        'designation', 
        'sports_equipment', 
        'sports_photos', 
        'fop_available', 
        'players_count', 
        'last_issued_date', 
        'status',
        'vendor_id',
        'vendor_assigned_date',
        'verification_status',
        'approval_status',
        'verification_datetime',
        'approval_rejection_datetime'
    ];

    public $timestamps = true; 

    public function hqSportsRequest()
    {
        return $this->hasOne(HQSportsRequest::class, 'sports_kit_requisition_id', 'id');
    }

    public function isVerified()
    {
        return $this->verification_status === 'Verified' || $this->approval_status === 'Approved';
    }
}
