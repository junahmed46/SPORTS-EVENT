<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class SportEventAthlete extends Model
{
    public function sport_event()
    {
        return $this->belongsTo('App\SportEvent','SE_id');

    }

    public function athlete()
    {
        return $this->belongsTo('App\Athlete','A_id');
    }

}
