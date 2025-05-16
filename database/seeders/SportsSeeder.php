<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sports = [
            ['id' => 1, 'sports_name' => 'Volleyball'],
            ['id' => 2, 'sports_name' => 'Football'],
            ['id' => 3, 'sports_name' => 'Basketball'],
            ['id' => 4, 'sports_name' => 'Handball'],
            ['id' => 5, 'sports_name' => 'Boxing'],
            ['id' => 6, 'sports_name' => 'Wrestling'],
            ['id' => 8, 'sports_name' => 'Judo'],
            ['id' => 9, 'sports_name' => 'Cricket'],
        ];

        foreach ($sports as &$sport) {
            $sport['created_at'] = Carbon::now();
            $sport['updated_at'] = Carbon::now();
        }

        DB::table('sports')->insert($sports);
    }
}
