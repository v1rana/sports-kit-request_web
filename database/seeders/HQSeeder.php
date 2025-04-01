<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HqsSeeder extends Seeder
{
    public function run()
    {
        DB::table('hqs')->insert([
           
            'hq_name' => 'HQ',
            'status' => 'approve',
            'mob' => '88888888',
        ]);
    }
}
