<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ✅ Add this line

class AdcSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('adcs')->insert([
            'name' => 'ADC',
            'designation' => 'ADC',
            'district' => 'FARIDABAD',
            'mob' => '7973972631',
        ]);
    }
}
