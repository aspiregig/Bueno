<?php namespace Bueno\Repositories;

use App\Models\BaseModel;
use App\Models\City;
use App\Models\Area;
use App\Models\Kitchen;
use App\Models\State;
use App\Models\Address;
use Bueno\Exceptions\ModelCannotBeDeletedException;

class DbLocationRepository extends  AbstractRepository {

  function __construct(BaseModel $model)
  {
    $this->model = $model;
  }

  public function getAllStates()
  {
    return State::get();
  }

  public function getAllActiveAreas()
  {
    return Area::where('status',1)->get();
  }

  public function getNotAssignedAreas()
  {
    $areas = Area::with('kitchen')->get();

    $areas = $areas->filter(function($area){
      return $area->kitchen->count() ? false : true;
    });

    return $areas;

  }

  public function getAllCities()
  {
    return City::get();
  }

  public function getAreaById($id)
  {
    return Area::find($id);
  }

  public function getCityById($id)
  {
    return City::whereId($id)->first();
  }

  public function getAllAreas()
  {
    return Area::get();
  } 

  public function getAreasByCityId($id)
  {
    return Area::whereCityId($id)->get();
  }

  public function getStateById($id)
  {
    return State::find($id);
  }

  public function updateAreaById($id,$inputs)
  {
    $area = Area::find($id);
    $area->fill($inputs)->save();
    return $area;
  }

  public function updateCityById($id,$inputs)
  {
    $city = City::whereId($id)->first();
    $area->fill($inputs)->save();
    return $area;
  }

  public function deleteAreaById($id)
  {
      $area = $this->getAreaById($id);
      $area->delete();
  }

  public function deleteCityById($id)
  {
      $city = $this->getCityById($id);
      $city->delete();
  }

  public function newCity($inputs)
  {
    return City::create($inputs);
  }

  public function newArea($inputs)
  {
    return Area::create($inputs);
  }

  public function newState($inputs)
  {
    return State::create($inputs);
  }

  public function setAreaStatusById($id,$status)
  {
      $area = Area::whereId($id)->first();
      $area->status = $status ;
      $area->save();
      return $city;
  }

  public function setCityStatusById($id,$status)
  {
      $city = City::whereId($id)->first();
      $city->status = $status ;
      $city->save();
      return $city;
  }

  public function setStateStatusById($id,$status)
  {
      $state = State::whereId($id)->first();
      $state->status = $status ;
      $state->save();
      return $state;
  }


  public function checkIfStateBelongsToKitchen($state_id)
  {
    $kitchens = Kitchen::all();

    $state_cities_ids = State::find($state_id)->cities->pluck('id')->toArray();

    foreach($kitchens as $kitchen)
    {
      $areas = $kitchen->areas;

      foreach($areas as $area)
      {
        $area_city_id = $area->city->id;

        if(in_array($area_city_id, $state_cities_ids))
        {
          throw new ModelCannotBeDeletedException('This state cannot be deleted as it has kitchen associated.');
        }
      }
    }
  }

  public function checkIfCityBelongsToKitchen($city_id)
  {
    $kitchens = Kitchen::all();

    $city  = City::find($city_id);

    foreach($kitchens as $kitchen)
    {
      $areas = $kitchen->areas;

      foreach($areas as $area)
      {
        $area_city_id = $area->city->id;

        if($area_city_id == $city->id)
        {
          throw new ModelCannotBeDeletedException('This city cannot be deleted as it has kitchen associated.');
        }
      }
    }
  }

  public function checkIfAreaBelongsToKitchen($area_id)
  {
      $area = Area::find($area_id);

      if($area->kitchen->count()>0) throw new ModelCannotBeDeletedException('This area cannot be deleted as it has kitchen associated.');
  }

  public function getSessionArea()
  {
    $area_id = session()->has('area_id') && session('area_id') ? (Integer) session('area_id') : null;

    if($area_id) return Area::find($area_id);

    return null;
  }

  public function getKitchensAreas()
  {
    $kitchens = Kitchen::all();
    $areas = collect([]);

    foreach($kitchens->where('status', 1) as $kitchen)
    {
      $areas = $areas->merge($kitchen->areas->where('status', 1));

      $areas = $areas->sortBy(function($area){
        return $area->name;
      });

    }
      return $areas;
  }

  public function checkIfAreaBelongsToUserAddress($area_id)
  {
    $addresses = Address::where('area_id',$area_id);

    if($addresses->count()>0)
    {
      throw new ModelCannotBeDeletedException('This area cannot be deleted as it has kitchen associated.');
    }

  }

}
