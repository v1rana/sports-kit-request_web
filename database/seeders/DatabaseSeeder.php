<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(CategoryWiseGradationsSeeder::class);
        $this->call(VendorSeeder::class);
        $this->call(SportsSeeder::class);
        $this->call(SportVendorSeeder::class);
        $this->call(DSOSeeder::class);
        
        $this->call(GramPanchayatSarpanchSeeder::class);
        $this->call(AdcSeeder::class);
        $this->call(HQSeeder::class);
       
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
