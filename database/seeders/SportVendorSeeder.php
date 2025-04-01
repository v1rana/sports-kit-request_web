<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SportVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'vendor_id' => 1, 'sport_id' => 1],
            ['id' => 2, 'vendor_id' => 1, 'sport_id' => 2],
            ['id' => 3, 'vendor_id' => 2, 'sport_id' => 3],
            ['id' => 4, 'vendor_id' => 2, 'sport_id' => 4],
            ['id' => 5, 'vendor_id' => 3, 'sport_id' => 5],
            ['id' => 6, 'vendor_id' => 3, 'sport_id' => 6],
            ['id' => 8, 'vendor_id' => 4, 'sport_id' => 8],
            ['id' => 9, 'vendor_id' => 5, 'sport_id' => 9],
        ];

        foreach ($data as &$row) {
            $row['created_at'] = Carbon::now();
            $row['updated_at'] = Carbon::now();
        }

        DB::table('sport_vendor')->insert($data);
    }
}
