<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class SportEventAthlete extends Model
{
    /**
     * Defining relation of sport_event with  with sport_event_athlete table
     */
    public function sport_event()
    {
        return $this->belongsTo('App\SportEvent','SE_id');

    }

    /**
     * Defining relation of sport_event_athlete with  with athlete table
     */
    public function athlete()
    {
        return $this->belongsTo('App\Athlete','A_id');
    }

}
