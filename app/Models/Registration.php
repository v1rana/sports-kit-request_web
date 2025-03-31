<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model {
    use HasFactory;

    protected $fillable = [
        'district', 'block', 'gov_type', 'gp_mb_list', 'name', 'designation', 'declaration_file'
    ];
}
