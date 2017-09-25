<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Athletes extends Model
{
    protected $fillable = [
        'first_name', 'sur_name',
    ];

    public function athlets()
    {
        return $this->hasMany('App\SportEventAthlete','A_id');
    }

}
