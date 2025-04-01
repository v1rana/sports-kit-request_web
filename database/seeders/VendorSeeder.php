<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            ['id' => 1, 'vendor_name' => 'Alpha Sports'],
            ['id' => 2, 'vendor_name' => 'Universal Fitness'],
            ['id' => 3, 'vendor_name' => 'Game Zone'],
            ['id' => 4, 'vendor_name' => 'ProSports Supplies'],
            ['id' => 5, 'vendor_name' => 'Sports Kingdom'],
        ];

        foreach ($vendors as &$vendor) {
            $vendor['created_at'] = Carbon::now();
            $vendor['updated_at'] = Carbon::now();
        }

        DB::table('vendors')->insert($vendors);
    }
}
