<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HQSportsRequest extends Model
{
    use HasFactory;

    protected $table = 'hq_sports_requests'; // Explicitly define table name

    protected $fillable = ['hq_id', 'sports_kit_requisition_id', 'status'];

    public function hq()
    {
        return $this->belongsTo(HQ::class, 'hq_id');
    }

    public function sportsKitRequisition()
    {
        return $this->belongsTo(SportsKitRequisition::class, 'sports_kit_requisition_id', 'id');
    }
}