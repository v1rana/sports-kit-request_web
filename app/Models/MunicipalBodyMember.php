<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MunicipalBodyMember extends Model {
    use HasFactory;

    protected $table = 'municipal_body_member';
    protected $fillable = ['district', 'block', 'municipal_area', 'wardmember', 'mob', 'otp', 'otp_expires_at'];
    public $timestamps = true;
}
