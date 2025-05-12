<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportsKitRequisition extends Model {
    use HasFactory;

    protected $table = 'sports_kit_requisitions';

    protected $fillable = [
        'applicant_id', 
        'name', 
        'district', 
        'block', 
        'area_name',
        'designation', 
        'specific_designation', 
        'sports_equipment', 
        'gram_municipal_signed_document', 
        'status',
        'vendor_id',
        'vendor_assigned_date',
        'verification_status',
        'not_verify_remarks',
        'reject_remarks',
        'disbursement_status',
        'approval_status',
        'verification_datetime',
        'approval_rejection_datetime',
        'vendor_assign_date'
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
