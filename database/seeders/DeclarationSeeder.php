<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeclarationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $points = [
            "I have read the Haryana Outstanding Sportspersons (Recruitment and Condition of Service) Rules, 2021 and declare that I am eligible for submission of my application for consideration of appointment under these Rules.",
            "I have enclosed self-attested copies of all documents in support of my application.",
            "I have played in 50% or more of the games played by team in the tournament.",
            "I did not represent a State/UT other than Haryana at the national level.",
            " I am not guilty of doping, sexual harassment and abuse, competitive manipulation like betting, inside information, match fixing, tanking, threatening the integrity and essence of sports.",
            " If appointment is offered, I undertake that I shall have no subsisting contract for pecuniary gains like commercial endorsement or professional sport before joining the service.",
            "I forego my earlier claim made under the Haryana Outstanding Sportsperson.",
            
        ];
    
        foreach ($points as $point) {
            \App\Models\Declaration::create(['point_text' => $point]);
        }
    }
}
