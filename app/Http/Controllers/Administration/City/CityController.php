<?php

namespace App\Http\Controllers\Administration\City;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\City\CityService;
use App\Http\Requests\City\CityRequest;

use App\Models\City;
use App\Models\Country;

class CityController extends Controller
{
    //
	protected $cityService;
	protected $cityData;
	
	public function __construct(cityService $cityService)
	{
		$this->cityService = $cityService;
		if (isset(request()->Id)) {
			$this->cityData = $this->cityService->getCity(request()->Id);
		}
		
	}
	
	public function index()
	{

		return view('pages.administration.settings.city.index',[
			'cities' => $this->cityService->getAllCity()
		]);
	}

	public function create()
	{
		return view('pages.administration.settings.city.create',[
			'countries' => $this->cityService->getAllCountry()	
		]);
	}

	public function store(CityRequest $request)
	{
		$this->cityService->createCity($request->all());
		
		return response()->json([
			'message' => "City Created Successfully"
		]);
	}

	public function edit()
	{
		return view('pages.administration.settings.city.edit',['city' => $this->cityData,'countries' => $this->cityService->getAllCountry()
	]);
	}
	
	public function update(CityRequest $request)
	{
		$this->cityService->updateCity($this->cityData,$request->all());
		return response()->json([
			'message' => "City Is Updated Successfully"
		]);
	}

	public function delete()
	{
		$this->cityData->delete();
		return response()->json([
			"message" => "City is Deleted Successfully"
		]);
	}
}
