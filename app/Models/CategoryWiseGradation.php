<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryWiseGradation extends Model
{
    use HasFactory;

    protected $table = 'category_wise_gradations'; // Your table name

    protected $fillable = ['category','tournament','organising_authority','medal','gradation'.'participation'.'gradation_participation']; // Define fillable columns
}
