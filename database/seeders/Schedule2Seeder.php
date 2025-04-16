<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Schedule2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('schedule_2')->insert([
            [ 'team_event_id' => 1, 'tournament' => 'Olympic Games', 'organizing_authority' => 'International Olympic Committee', 'organizing_authority_abbr' => 'IOC', 'gold' => 'Group A', 'silver' => 'Group A', 'bronze' => 'Group B', 'participation' => 'Group C', 'remarks' => '' ],
            [ 'team_event_id' => 1, 'tournament' => 'Paralympics', 'organizing_authority' => 'International Paralympic Committee', 'organizing_authority_abbr' => 'IPC', 'gold' => 'Group B', 'silver' => 'Group B', 'bronze' => 'Group B', 'participation' => NULL, 'remarks' => '' ],
        
            [ 'team_event_id' => 2, 'tournament' => 'Asian Games', 'organizing_authority' => 'Olympic Council of Asia', 'organizing_authority_abbr' => 'OCA', 'gold' => 'Group B', 'silver' => 'Group B', 'bronze' => 'Group C', 'participation' => 'Group C', 'remarks' => '' ],
            [ 'team_event_id' => 2, 'tournament' => 'Asian Para Games', 'organizing_authority' => 'Asian Paralympic Committee', 'organizing_authority_abbr' => 'APC', 'gold' => 'Group C', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL, 'remarks' => '' ],
        
            [ 'team_event_id' => 3, 'tournament' => '4-years World Cup/Championship', 'organizing_authority' => 'International Federation of concerned games recognized by IOC', 'organizing_authority_abbr' => 'IOC', 'gold' => 'Group B', 'silver' => 'Group B', 'bronze' => 'Group C', 'participation' => 'Group C', 'remarks' => '(Sports events included in the Olympic Games only)' ],
        
            [ 'team_event_id' => 4, 'tournament' => 'World Championship', 'organizing_authority' => 'International Federation  of concerned games recognized by IOC', 'organizing_authority_abbr' => 'IOC', 'gold' => 'Group C', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL, 'remarks' => '(Less than 4 years) (Sports events included in the Olympic Games only)' ],
        
            [ 'team_event_id' => 5, 'tournament' => 'Commonwealth Games', 'organizing_authority' => 'Commonwealth Games Federation', 'organizing_authority_abbr' => 'CGF', 'gold' => 'Group C', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL, 'remarks' => '' ],
            [ 'team_event_id' => 5, 'tournament' => 'Commonwealth Para Games', 'organizing_authority' => 'Commonwealth Games Federation', 'organizing_authority_abbr' => 'CGF', 'gold' => 'Group C', 'silver' => 'Group C', 'bronze' => NULL, 'participation' => NULL, 'remarks' => '' ],
        
            [ 'team_event_id' => 6, 'tournament' => 'World University Games', 'organizing_authority' => 'International University Sports Federation', 'organizing_authority_abbr' => 'IUSF', 'gold' => NULL, 'silver' => NULL, 'bronze' => NULL, 'participation' => NULL, 'remarks' => '(Sports events included in the Olympic Games only)' ],
        
            [ 'team_event_id' => 7, 'tournament' => '4-years Asian Championship', 'organizing_authority' => 'Asian Federation of concerned games affiliated to OCA or International Federation recognized by IOC', 'organizing_authority_abbr' => '', 'gold' => 'Group C', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL, 'remarks' => '' ],
        
            [ 'team_event_id' => 8, 'tournament' => 'Special Olympics', 'organizing_authority' => 'Only those which are recognized by IOC', 'organizing_authority_abbr' => '', 'gold' => 'Group C', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL, 'remarks' => '(Sports events included in the Olympic Games only)' ],
        
            [ 'team_event_id' => 9, 'tournament' => 'Deaflympics/4 years Deaf-World Cup/Championship/Circle Kabaddi 4 years Asian championship', 'organizing_authority' => 'International Committee of Sports for the Deaf', 'organizing_authority_abbr' => 'ICSD', 'gold' => 'Group C', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL, 'remarks' => '(Sports events included in the Olympic Games only)' ],
        
            [ 'team_event_id' => 10, 'tournament' => 'South Asian Games', 'organizing_authority' => 'South Asian Games Federation', 'organizing_authority_abbr' => 'SAGF', 'gold' => 'Group C', 'silver' => NULL, 'bronze' => NULL, 'participation' => NULL, 'remarks' => '' ],
        
            [ 'team_event_id' => 11, 'tournament' => '4-years Blind Cricket World Cup', 'organizing_authority' => 'World Blind Cricket Council', 'organizing_authority_abbr' => 'WBCC', 'gold' => NULL, 'silver' => NULL, 'bronze' => NULL, 'participation' => NULL, 'remarks' => '' ],
        
            [ 'team_event_id' => 12, 'tournament' => 'Ranji Trophy (Cricket)', 'organizing_authority' => 'Board of Control for Cricket in India', 'organizing_authority_abbr' => 'BCCI', 'gold' => 'Group C', 'silver' => NULL, 'bronze' => NULL, 'participation' => NULL, 'remarks' => '' ],
        
            [ 'team_event_id' => 13, 'tournament' => '4 years World Cup/2 years Asian Chapmpionship', 'organizing_authority' => 'International Kabaddi Federation', 'organizing_authority_abbr' => 'IKF', 'gold' => 'Group C', 'silver' => 'Group C', 'bronze' => NULL, 'participation' => NULL, 'remarks' => '(Circle Kabaddi)' ],
        
            [ 'team_event_id' => 14, 'tournament' => 'National Games', 'organizing_authority' => 'Indian Olympic Association', 'organizing_authority_abbr' => 'IOA', 'gold' => 'Group C', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL, 'remarks' => '' ],
        ]);
        
        
    }
}
