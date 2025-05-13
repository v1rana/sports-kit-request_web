<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DsoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Truncate the table before inserting new data (optional)
        DB::table('dsos')->truncate();

        // DSO List Data
        $dsos = [
            ['name' => 'Rajbir Singh Ranga', 'email' => 'dsoamb@gmail.com', 'district' => 'Ambala', 'mob' => '9041063077'],
            ['name' => 'Nisha', 'email' => 'dsobh@gmail.com', 'district' => 'Bhiwani', 'mob' => '9728198706'],
            ['name' => 'Sandhu Bala', 'email' => 'dsochdadri@gmail.com', 'district' => 'CHARKHI - DADRI', 'mob' => '9992062068'],
            ['name' => 'Asha Rani', 'email' => 'dsofbd@gmail.com', 'district' => 'Faridabad', 'mob' => '8368575610'],
            ['name' => 'Sh. Vishnu Das', 'email' => 'dsofhb@gmail.com', 'district' => 'Fatehabad', 'mob' => '8168139626'],
            ['name' => 'Girraj Singh', 'email' => 'dsoggn7@gmail.com', 'district' => 'Gurgaon', 'mob' => '9810778257'],
            ['name' => 'Naresh', 'email' => 'dsohsr@gmail.com', 'district' => 'Hisar', 'mob' => '9466351934'],
            ['name' => 'Satender', 'email' => 'dsojjr@gmail.com', 'district' => 'Jhajjar', 'mob' => '9215107575'],
            ['name' => 'Rampal', 'email' => 'dsojnd@gmail.com', 'district' => 'Jind', 'mob' => '9717862964'],
            ['name' => 'Nirmala', 'email' => 'dsokth@gmail.com', 'district' => 'Kaithal', 'mob' => '9467422766'],
            ['name' => 'Sudha Bhasin', 'email' => 'dsoknl@gmail.com', 'district' => 'Karnal', 'mob' => '9990187006'],
            ['name' => 'Manoj', 'email' => 'dsokkr@gmail.com', 'district' => 'Kurukshetra', 'mob' => '7015975496'],
            ['name' => 'Sh. Ram Mehar', 'email' => 'dsomwt@gmail.com', 'district' => 'Mewat', 'mob' => '9468257749'],
            ['name' => 'Narender Kundu', 'email' => 'dsonrnl@gmail.com', 'district' => 'Mahendragarh', 'mob' => '9468257749'],
            ['name' => 'Anil Kumar', 'email' => 'dsopwl@gmail.com', 'district' => 'Palwal', 'mob' => '9896126664'],
            ['name' => 'Neel Kamal', 'email' => 'dsopkl0@gmail.com', 'district' => 'Panchkula', 'mob' => '9814867979'],
            ['name' => 'Sh. Dhurander', 'email' => 'dsopnt@gmail.com', 'district' => 'Panipat', 'mob' => '8307096639'],
            ['name' => 'Pawan', 'email' => 'dsorwr@gmail.com', 'district' => 'Rewari', 'mob' => '8307096639'],
            ['name' => 'Sh. Manoj Kumar', 'email' => 'dsortk@gmail.com', 'district' => 'Rohtak', 'mob' => '8708730170'],
            ['name' => 'Jagdeep Singh', 'email' => 'dsosirsa@gmail.com', 'district' => 'Sirsa', 'mob' => '9996100010'],
            ['name' => 'Sh. Manoj Kumar', 'email' => 'dsosnp@gmail.com', 'district' => 'Sonipat', 'mob' => '8607298950'],
            ['name' => 'Shilpa Gupta', 'email' => 'dsoynagar@gmail.com', 'district' => 'Yamunanagar', 'mob' => '7988343752'],
        ];

        // Insert Data
        DB::table('dsos')->insert($dsos);
    }
}
