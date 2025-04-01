<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GramPanchayatSarpanchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gram_panchayat_sarpanch')->insert([
            'id'               => 1,
            'district'         => 'BHIWANI',
            'block'            => 'KAIRU',
            'gram_panchayat'   => 'BABARWAS',
            'sarpanch'         => 'Rajender Singh',
            'mob'              => '9813503099'
        ]);
    }
}
