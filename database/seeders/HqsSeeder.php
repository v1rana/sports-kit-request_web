<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 

class HqsSeeder extends Seeder
{
    public function run()
    {
        DB::table('hqs')->insert([
            'hq_name' => 'HQ',
            'status' => 'Active',
            'mob' => '9816675678',
        ]);
    }
}
