<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Schedule1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('schedule_1')->insert([
            [ 'individual_event_id' => 1, 'tournament' => 'Olympic Games', 'organizing_authority' => 'International Olympic Committee', 'organizing_authority_abbr' => 'IOC', 'gold' => 'Group A', 'silver' => 'Group A', 'bronze' => 'Group B', 'participation' => 'Group C','remarks' => '' ],
            [ 'individual_event_id' => 1, 'tournament' => 'Paralympics', 'organizing_authority' => 'International Paralympic Committee', 'organizing_authority_abbr' => 'IPC', 'gold' => 'Group B', 'silver' => 'Group B', 'bronze' => 'Group B', 'participation' => NULL,'remarks' => '' ],
        
            [ 'individual_event_id' => 2, 'tournament' => 'Asian Games', 'organizing_authority' => 'Olympic Council of Asia', 'organizing_authority_abbr' => 'OCA', 'gold' => 'Group A', 'silver' => 'Group B', 'bronze' => 'Group B', 'participation' => 'Group C','remarks' => '' ],
            [ 'individual_event_id' => 2, 'tournament' => 'Asian Para Games', 'organizing_authority' => 'Asian Paralympic Committee', 'organizing_authority_abbr' => 'APC', 'gold' => 'Group B', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL,'remarks' => '' ],
        
            [ 'individual_event_id' => 3, 'tournament' => '4-years World Cup/Championship', 'organizing_authority' => 'International Federation of concerned games recognized by IOC', 'organizing_authority_abbr' => '', 'gold' => 'Group B', 'silver' => 'Group B', 'bronze' => 'Group B', 'participation' => 'Group C','remarks' => '(Sports events included in the Olympic Games only)' ],
        
            [ 'individual_event_id' => 4, 'tournament' => 'World Cup/Championship', 'organizing_authority' => 'International Federation of concerned games recognized by IOC', 'organizing_authority_abbr' => '', 'gold' => 'Group B', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL,'remarks' => '(less than 4 years) (Sports events included in the Olympic Games only)' ],
        
            [ 'individual_event_id' => 5, 'tournament' => 'Commonwealth Games', 'organizing_authority' => 'Commonwealth Games Federation', 'organizing_authority_abbr' => 'CGF', 'gold' => 'Group B', 'silver' => 'Group B', 'bronze' => 'Group C', 'participation' => NULL,'remarks' => '' ],
            [ 'individual_event_id' => 5, 'tournament' => 'Commonwealth Para Games', 'organizing_authority' => 'Commonwealth Games Federation', 'organizing_authority_abbr' => 'CGF', 'gold' => 'Group C', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL,'remarks' => '' ],
        
            [ 'individual_event_id' => 6, 'tournament' => 'World University Games', 'organizing_authority' => 'International University Sports Federation', 'organizing_authority_abbr' => 'IUSF', 'gold' => 'Group B', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL,'remarks' => '(Sports events included in the Olympic Games only)' ],
        
            [ 'individual_event_id' => 7, 'tournament' => '4-years Asian Championship ', 'organizing_authority' => 'Asian Federation affiliated to OCA or International Federation  of concerned games recognized by IOC', 'organizing_authority_abbr' => '', 'gold' => 'Group B', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL,'remarks' => '(Sports events included in the Olympic Games only)' ],
        
            [ 'individual_event_id' => 8, 'tournament' => 'Special Olympics', 'organizing_authority' => 'Only those which are recognized by IOC', 'organizing_authority_abbr' => '', 'gold' => 'Group B', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL,'remarks' => '(Sports events included in the Olympic Games only)' ],
        
            [ 'individual_event_id' => 9, 'tournament' => 'Deaflympics/4 years Deaf-World Cup/Championship/4 years Para World Cup/Championship', 'organizing_authority' => 'International Committee of Sports for the Deaf / International Paralympic Committee', 'organizing_authority_abbr' => 'ICSD/IPC', 'gold' => 'Group B', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL,'remarks' => '(Sports events included in the Olympic Games only)' ],
        
            [ 'individual_event_id' => 10, 'tournament' => 'South Asian Games', 'organizing_authority' => 'South Asian Games Federation', 'organizing_authority_abbr' => 'SAGF', 'gold' => 'Group C', 'silver' => NULL, 'bronze' => NULL, 'participation' => NULL,'remarks' => '' ],
        
            [ 'individual_event_id' => 11, 'tournament' => 'National Games', 'organizing_authority' => 'Indian Olympic Association', 'organizing_authority_abbr' => 'IOA', 'gold' => 'Group C', 'silver' => 'Group C', 'bronze' => 'Group C', 'participation' => NULL,'remarks' => '' ],
        ]);
        
    }
}
