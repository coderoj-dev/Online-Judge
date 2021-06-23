<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $fillable = [
        'country_id','name','time_zone'
    ];

    public function country()
    {
       return $this->belongsTo(Country::class);
    }
  
}
