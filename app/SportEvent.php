<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class SportEvent extends Model
{

    public function sport_event_athlets()
    {
        return $this->hasMany('App\SportEventAthlete','SE_id');


    }
}
