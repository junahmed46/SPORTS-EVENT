<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class SportEvent extends Model
{

    /**
     * Defining relation of sport_events with  with sport_event_athlete table
     */
    public function sport_event_athlets()
    {
        return $this->hasMany('App\SportEventAthlete','SE_id');


    }
}
