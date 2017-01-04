<?php

namespace App\Http\Controllers\Admin;

use Bueno\Exceptions\ModelCannotBeDeletedException;
use Flash;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Validations\CreateAreaValidator as AreaValidator;
use Bueno\Repositories\DbLocationRepository as LocationRepo;

class AreaController extends Controller
{
    /**
    * @var AreaValidator
    * @var LocationRepo
    */
    protected $locationRepo,$areaValidator;

    function __construct(LocationRepo $locationRepo,AreaValidator $areaValidator) 
    {
        $this->locationRepo = $locationRepo;
        $this->areaValidator = $areaValidator;
        $this->middleware('access.location');
    }

  /**
   * Display a listing of areas
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index()
    {
        $areas = $this->locationRepo->getAllAreas();
        $page = 'datatables';
        return view('admin.area.areas',compact('page','areas'));
    }

    /**
     * Show the form for creating a new area.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {   
        $cities = $this->locationRepo->getAllCities();

        $page = 'datatables';

        if($cities->count() == 0)
          {
            $states = $this->locationRepo->getAllStates();
            if($states->count() == 0)
            {
              Flash::info('add at least one State to Add a new City/Area');
              return redirect()->route('admin.new_state');
            }
            Flash::info('add at least one City to Add a new Area');
            return redirect()->route('admin.new_city');
          }
        return view('admin.area.new_area',compact('page','cities'));
    }

    /**
     * Store a newly created area in area.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\ResponseRedirect
     */
    public function store(Request $request)
    {

        $inputs = $request->all();

        try
        {
          $this->areaValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_area'))->withErrors($e->getErrors())->withInput();
        }

        $new_area = $this->locationRepo->newArea($inputs);

        Flash::success('Area Created !');

        return redirect()->route('admin.areas');
    }

    /**
     * Show the form for editing the specified area.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $cities = $this->locationRepo->getAllCities();
        $area = $this->locationRepo->getAreaById($id);
        $page = 'datatables';
        return view('admin.area.update_area',compact('page','area','cities'));
    }

    /**
     * Update the specified area in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\ResponseReDirect
     */
    public function update(Request $request, $id)
    {   
        $inputs = $request->all();
        try
        {
          $this->areaValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.update_area',$id))->withErrors($e->getErrors())->withInput();
        }
        $area = $this->locationRepo->updateAreaById($id,$inputs);

        if($request->get('status')=='1')
        {   
            $city = $this->locationRepo->setCityStatusById($area->city_id,1);
            $state = $this->locationRepo->setStateStatusById($city->state_id,1);
        }
        Flash::success('Area Successfully Updated');
        return redirect()->route('admin.update_area',$area->id);
    }

    /**
     * Remove the specified area from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\ResponseRedirect
     */
    public function destroy($id)
    {
//        try
//        {
//          $this->locationRepo->checkIfAreaBelongsToKitchen($id);
//          $this->locationRepo->checkIfAreaBelongsToUserAddress($id);
//        }
//        catch(ModelCannotBeDeletedException $e)
//        {
//          Flash::danger($e->getMessage());
//
//          return redirect(route('admin.update_area', $id));
//        }
//        $this->locationRepo->deleteAreaById($id);
//
//        Flash::success('Area Deleted');

        return redirect()->route('admin.areas');
    }
}
