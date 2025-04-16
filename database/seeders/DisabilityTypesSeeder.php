<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DisabilityTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('disability_types')->insert([
            [ 'type' => 'Para', ],
            [ 'type' => 'Blind', ],
            [ 'type' => 'Deaf', ],
            [ 'type' => 'Special Olympic-Sports', ],
        
        ]);
    }
}
