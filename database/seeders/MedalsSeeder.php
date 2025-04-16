<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MedalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('medals')->insert([
            [ 'name' => 'Gold', ],
            [ 'name' => 'Silver', ],
            [ 'name' => 'Bronze', ],
            [ 'name' => 'Participation', ],
        
        ]);
    }
}
