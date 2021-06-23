<?php

namespace App\Services\City;

use App\Models\City;
use App\Models\Country;

class CityService
{
    public function createCity($data)
    {
        City::create($data);
    }

    public function getAllCity()
    {
        return City::all();
    }

    public function getCity($Id)
    {
        return City::findOrFail($Id);
    }

    public function updateCity(City $city,$data)
    {
        
        $city->update($data);
    }
    public function getAllCountry()
    {
        return Country::all();
    }

}