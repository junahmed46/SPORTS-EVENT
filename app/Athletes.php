<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Athletes extends Model
{
    protected $fillable = [
        'first_name', 'sur_name',
    ];

    /**
     * Defining relation of athlets with sport_event_athlete table
     */
    public function athlets()
    {
        return $this->hasMany('App\SportEventAthlete','A_id');
    }

}
