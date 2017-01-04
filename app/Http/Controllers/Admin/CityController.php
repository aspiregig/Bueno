<?php

namespace App\Http\Controllers\Admin;

use Bueno\Exceptions\ModelCannotBeDeletedException;
use Flash;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Validations\CreateCityValidator as CityValidator;
use Bueno\Repositories\DbLocationRepository as LocationRepo;

class CityController extends Controller
{   

    /**
    * @var CitytValidator
    * @var LocationRepo
    */
    protected $cityValidator,$locationRepo;


    /**
    * @param TestValidator $testValidator
    * @param LocationRepo $locationRepo
    */
    function __construct(CityValidator $cityValidator,LocationRepo $locationRepo)
    {
      $this->cityValidator = $cityValidator;
      $this->locationRepo = $locationRepo;
      $this->middleware('access.location');
    }
    
    /**
     * Display a listing of the cities.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = $this->locationRepo->getAllCities();
        $page = 'datatables';
        return view('admin.city.cities',compact('page','cities'));
    }

    /**
     * Show the form for creating a new city.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = $this->locationRepo->getAllStates();

        $page = 'datatables';
        if($states->count() == 0)
        {
          Flash::info('add at least one State to add a new City/Area.');
          return view('admin.state.new_state',compact('page'));
        }
        return view('admin.city.new_city',compact('page','states'));
    }

    /**
     * Store a newly created city in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();

        try
        {
          $this->cityValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_city'))->withErrors($e->getErrors())->withInput();
        }

        $new_city = $this->locationRepo->newCity($request->all());

        Flash::success("City Created !");

        return redirect(route('admin.cities'));

    }

    /**
     * Display the specified city.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified city.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $states = $this->locationRepo->getAllStates();
        $city = $this->locationRepo->getCityById($id);
        $page = 'datatables';
        return view('admin.city.update_city',compact('page','city','states'));
    }

    /**
     * Update the specified city in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $city = $this->locationRepo->getCityById($id);
        $input = $request->all();

        if($request->get('status')==='0')
        {   
            $areas = $this->locationRepo->getAreasByCityId($id);
            foreach($areas as $area)
            {
                $area->status=0;
                $area->save();
            }
        }
        else
        {
            $state = $this->locationRepo->getStateById($city->state_id);
            $state->status = '1' ;
            $state->save();
        }
        $city->fill($input)->save();

        Flash::success('City Updated');

        return redirect()->route('admin.cities'); 
    }

    /**
     * Remove the specified city from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//      try
//      {
//        $this->locationRepo->checkIfCityBelongsToKitchen($id);
//      }
//      catch(ModelCannotBeDeletedException $e)
//      {
//        Flash::danger($e->getMessage());
//
//        return redirect(route('admin.update_city', $id));
//      }
//
//        $city = $this->locationRepo->getCityById($id);
//        $areas = $city->areas();
//        $areas->delete();
//        $city->delete();
//
//      Flash::success('City Deleted !');

      return redirect()->route('admin.cities');
    }

}
