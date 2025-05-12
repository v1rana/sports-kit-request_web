<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentVendorAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'equipment_name',
        'vendor_id',
        'procurement_amount',
        'bill_no',
        'voucher_file_path',
        'fund_source',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function requisition()
    {
        return $this->belongsTo(SportsKitRequisition::class, 'request_id');
    }
}
