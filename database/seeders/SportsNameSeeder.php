<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB Facade

class SportsNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $sports = [
            '3x3 Basketball', 'Archery', 'Artistic Swimming', 'Athletics', 'Badminton',
            'Baseball', 'Basketball', 'Boxing', 'Canoeing', 'Cycling', 'Diving',
            'Equestrian', 'Fencing', 'Field Hockey', 'Football', 'Golf', 'Gymnastics',
            'Handball', 'Judo', 'Karate', 'Modern Pentathlon', 'Rowing', 'Rugby Sevens',
            'Sailing', 'Shooting', 'Skateboarding', 'Softball', 'Sport Climbing',
            'Surfing', 'Swimming', 'Table Tennis', 'Taekwondo', 'Tennis', 'Triathlon',
            'Volleyball', 'Water Polo', 'Weightlifting', 'Wrestling'
        ];

        foreach ($sports as $sport) {
            DB::table('sport_names')->updateOrInsert(['name' => $sport], ['name' => $sport]);
        }

        $states = [
            'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa', 'Gujarat',
            'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh',
            'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab',
            'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttarakhand', 'Uttar Pradesh',
            'West Bengal', 'Andaman and Nicobar Islands', 'Chandigarh', 'Dadra and Nagar Haveli and Daman & Diu',
            'The Government of NCT of Delhi', 'Jammu & Kashmir', 'Ladakh', 'Lakshadweep', 'Puducherry'
        ];

        $stateIds = [];
        foreach ($states as $state) {
            $stateIds[$state] = DB::table('state_names')->updateOrInsert(['name' => $state], ['name' => $state]);
        }

        $districts = [
            ['name' => 'Ambala', 'state' => 'Haryana'], ['name' => 'Bhiwani', 'state' => 'Haryana'], 
            ['name' => 'CHARKHI - DADRI', 'state' => 'Haryana'], ['name' => 'Faridabad', 'state' => 'Haryana'], 
            ['name' => 'Fatehabad', 'state' => 'Haryana'], ['name' => 'Gurgaon', 'state' => 'Haryana'], 
            ['name' => 'Hisar', 'state' => 'Haryana'], ['name' => 'Jhajjar', 'state' => 'Haryana'], 
            ['name' => 'Jind', 'state' => 'Haryana'], ['name' => 'Kaithal', 'state' => 'Haryana'], 
            ['name' => 'Karnal', 'state' => 'Haryana'], ['name' => 'Kurukshetra', 'state' => 'Haryana'], 
            ['name' => 'Mewat', 'state' => 'Haryana'], ['name' => 'Mahendragarh', 'state' => 'Haryana'], 
            ['name' => 'Palwal', 'state' => 'Haryana'], ['name' => 'Panchkula', 'state' => 'Haryana'], 
            ['name' => 'Panipat', 'state' => 'Haryana'], ['name' => 'Rewari', 'state' => 'Haryana'], 
            ['name' => 'Rohtak', 'state' => 'Haryana'], ['name' => 'Sirsa', 'state' => 'Haryana'], 
            ['name' => 'Sonipat', 'state' => 'Haryana'], ['name' => 'Yamunanagar', 'state' => 'Haryana']
        ];

        foreach ($districts as $district) {
            $stateId = DB::table('state_names')->where('name', $district['state'])->value('id');
            DB::table('districts')->updateOrInsert(
                ['name' => $district['name']], 
                ['name' => $district['name'], 'state_id' => $stateId]
            );
        }
    }
}
