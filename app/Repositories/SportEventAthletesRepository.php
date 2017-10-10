<?php
namespace App\Repositories;
use App\Athletes;
use App\SportEventAthlete;
use App\AthleteProgress;

class SportEventAthletesRepository {
    /**
     * Assign random (10 to 15) athelets from Athlete tables to Sport event Athlete
     *
     * @param  SE_id -> integer (Should be primary key to sport_events tables)
     * @return true for now but we can add checks like if event is not found or there is no athlets, i kept it simple to not make it complex
     */
    public function get_and_assign_rand_athlete($SE_id)
    {

        $athlets = Athletes::inRandomOrder()->limit(rand(10,15))->get();

        $athlets_data = [];
        $i = 1;
        foreach($athlets as $row)
        {
            $code_identifier = md5(microtime()."_".$i."_".$SE_id."_".$SE_id);
            $athlets_data[] = [
                'SE_id' => $SE_id,
                'A_id' => $row->id,
                'start_number' => $i,
                'code_identifier' => $code_identifier,

            ];
            $i++;
        }

        SportEventAthlete::insert($athlets_data);

        return true;
    }

    /**
     * get the id of Sport event Athletes
     *
     * @param  identifer -> normally md5 32 (Should be present in  Sport event Athletes table)
     * @return id of Sport event Athletes table row or false
     */
    public function get_athlete_by_identifier($identifer)
    {
        return SportEventAthlete::where('code_identifier','=',$identifer)->first();
    }

    /**
     * get the Last step of the sport event
     *
     * @param  identifer -> normally md5 32 (Should be present in  Sport event Athletes table)
     * @return id of Sport event Athletes table row or false
     */
    public function get_last_step($se_id)
    {
        return AthleteProgress::join('sport_event_athletes as sea','athlete_progress.SEA_id','=','sea.id')
            ->where('SE_id','=',$se_id)->orderby('athlete_progress.id','desc')->first();
    }





}