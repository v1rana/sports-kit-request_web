<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GramPanchayatSarpanch extends Model {
    use HasFactory;

    protected $table = 'gram_panchayat_sarpanch';
    protected $fillable = ['district', 'block', 'gram_panchayat', 'sarpanch', 'mob', 'otp', 'otp_expires_at'];
    public $timestamps = true;
}
