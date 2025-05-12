<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportVendor extends Model
{
    protected $table = 'sport_vendor';

    protected $fillable = [
        'vendor_id',
        'sport_id',
        'equipment',
        'rate',
        'photo',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
}

