<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $games = [
            ['name' => 'Chess', 'is_para' => 0],
            ['name' => 'Athletics', 'is_para' => 0],
            ['name' => 'Archery', 'is_para' => 0],
            ['name' => 'Badminton', 'is_para' => 0],
            ['name' => 'Basketball', 'is_para' => 0],
            ['name' => 'Boxing', 'is_para' => 0],
            ['name' => 'Cycling', 'is_para' => 0],
            ['name' => 'Fencing', 'is_para' => 0],
            ['name' => 'Judo', 'is_para' => 0],
            ['name' => 'Kabaddi', 'is_para' => 0],
            ['name' => 'Tennis - Lawn Tennis', 'is_para' => 0],
            ['name' => 'Shooting', 'is_para' => 0],
            ['name' => 'Softball', 'is_para' => 0],
            ['name' => 'Volleyball', 'is_para' => 0],
            ['name' => 'Wrestling', 'is_para' => 0],
            ['name' => 'Wushu', 'is_para' => 0],
            ['name' => 'Marathon Swimming', 'is_para' => 0],
            ['name' => 'Water polo', 'is_para' => 0],
            ['name' => 'Basketball [3x3]', 'is_para' => 0],
            ['name' => 'Breakdancing', 'is_para' => 0],
            ['name' => 'Cricket', 'is_para' => 0],
            ['name' => 'Dragon boat', 'is_para' => 0],
            ['name' => 'Equestrian', 'is_para' => 0],
            ['name' => 'Modern Pentathlon', 'is_para' => 0],
            ['name' => 'Baseball', 'is_para' => 0],
            ['name' => 'Canoeing', 'is_para' => 0],
            ['name' => 'Football', 'is_para' => 0],
            ['name' => 'Gymnastics', 'is_para' => 0],
            ['name' => 'Handball', 'is_para' => 0],
            ['name' => 'Hockey', 'is_para' => 0],
            ['name' => 'Netball', 'is_para' => 0],
            ['name' => 'Rowing', 'is_para' => 0],
            ['name' => 'Swimming', 'is_para' => 0],
            ['name' => 'Table-Tennis', 'is_para' => 0],
            ['name' => 'Taekwondo', 'is_para' => 0],
            ['name' => 'Weightlifting', 'is_para' => 0],
            ['name' => 'Artistic Swimming', 'is_para' => 0],
            ['name' => 'Diving', 'is_para' => 0],
            ['name' => 'Martial Art - Karate', 'is_para' => 0],
            ['name' => 'Golf', 'is_para' => 0],
            ['name' => 'Martial Art - Kurash', 'is_para' => 0],
            ['name' => 'Esports', 'is_para' => 0],
            ['name' => 'Roller Sports - Skateboarding', 'is_para' => 0],
            ['name' => 'Rugby Sevens', 'is_para' => 0],
            ['name' => 'Sailing', 'is_para' => 0],
            ['name' => 'Sepak Takraw', 'is_para' => 0],
            ['name' => 'Sports Climbing', 'is_para' => 0],
            ['name' => 'Squash', 'is_para' => 0],
            ['name' => 'Martial Art -Ju-jitsu', 'is_para' => 0],
            ['name' => 'Bridge', 'is_para' => 0],
            ['name' => 'Tennis - Soft Tennis', 'is_para' => 0],
            ['name' => 'Triathlon', 'is_para' => 0],
            ['name' => 'Beach Volleyball', 'is_para' => 0],
            ['name' => 'Surfing', 'is_para' => 0],
            ['name' => 'Lawn Bowls', 'is_para' => 0],

            // Para Games
            ['name' => 'Para Game - Boccia', 'is_para' => 1],
            ['name' => 'Para Game - Goalball', 'is_para' => 1],
            ['name' => 'Para Game - Football 5 a side', 'is_para' => 1],
            ['name' => 'Para Game - Sitting Volleyball', 'is_para' => 1],
            ['name' => 'Para Game - Wheelchair Basketball', 'is_para' => 1],
            ['name' => 'Para Game - Wheelchair Fencing', 'is_para' => 1],
            ['name' => 'Para Game - Wheelchair Tennis', 'is_para' => 1],
            ['name' => 'Para Game - Para Power Lifting', 'is_para' => 1],
            ['name' => 'Para Game - Paracanoe', 'is_para' => 1],
            ['name' => 'Para Game - Wheelchair Rugby', 'is_para' => 1],
        ];

        DB::table('games')->insert($games);

    }
}
