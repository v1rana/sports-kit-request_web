<?php

namespace Database\Seeders;

use App\Models\User;
use HqsSeeder;
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
        $this->call(SportsNameSeeder::class);
        
        $this->call(VendorSeeder::class);
        $this->call(SportsSeeder::class);
        $this->call(SportVendorSeeder::class);
        $this->call(DsoSeeder::class);
        
        $this->call(GramPanchayatSarpanchSeeder::class);
        $this->call(AdcSeeder::class);
        // $this->call(HqsSeeder::class);
       
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);



        // job form seeder

        $this->call(Schedule12Seeder::class);
        // $this->call(Schedule2Seeder::class);
        $this->call(DisabilityTypesSeeder::class);
        $this->call(MedalsSeeder::class);
        $this->call(GamesSeeder::class);
    }
}
