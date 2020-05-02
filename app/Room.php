<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{   
    public function calendars()
    {
        return $this->hasMany('App\Calendar');
    }
}
