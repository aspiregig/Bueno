<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use Flash;
use App\Http\Requests;
use Bueno\Exceptions\ModelCannotBeDeletedException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbLocationRepository as LocationRepo;
use Bueno\Validations\CreateStateValidator as StateValidator;

class StateController extends Controller
{
    /**
    * @var StatetValidator
    * @var LocationRepo
    */
    protected $stateValidator,$locationRepo;


    /**
    * @param StateValidator $stateValidator
    * @param LocationRepo $locationRepo
    */
    function __construct(StateValidator $stateValidator,LocationRepo $locationRepo)
    {
      $this->stateValidator = $stateValidator;
      $this->locationRepo = $locationRepo;
      $this->middleware('access.location');
    }

    /**
     * Display a listing of the states.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $states = $this->locationRepo->getAllStates();
        $page = 'datatables';

        return view('admin.state.states',compact('page','states'));
    }

    /**
     * Show the form for creating a new state.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        
        $page = 'datatables';
        return view('admin.state.new_state',compact('page'));
    }

    /**
     * Store a newly created state in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\ResponseRedirect
     */
    public function store(Request $request)
    {

        $inputs = $request->all();

        try
        {
          $this->stateValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_state'))->withErrors($e->getErrors())->withInput();
        }

        $new_state = $this->locationRepo->newState($inputs);

        Flash::success('State Added');

        return redirect()->route('admin.states');
    }

    /**
     * Show the form for editing the specified state.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $state = $this->locationRepo->getStateById($id);
        $page = 'datatables';
        return view('admin.state.update_state',compact('page','state'));
    }

    /**
     * Update the specified state in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->all();

        try
        {
          $this->stateValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.update_state',$id))->withErrors($e->getErrors())->withInput();
        }

        $state = $this->locationRepo->getStateById($id);
        $state->fill($inputs)->save();
        
        if($request->get('status')=='0')
        {   
            $cities = $this->locationRepo->getStateById($id)->cities;
            foreach($cities as $city)
            {
                $city->status=0;
                $city->save();
                $areas = $city->areas;
                foreach($areas as $area)
                {
                    $area->status=0;
                    $area->save();
                }
            }
        }

        Flash::success('State Updated !');

        return redirect()->route('admin.states'); 
    }

    /**
     * Remove the specified state from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\ResponseRedirect
     */
    public function destroy($id)
    {
//      try
//      {
//        $this->locationRepo->checkIfStateBelongsToKitchen($id);
//      }
//      catch(ModelCannotBeDeletedException $e)
//      {
//        Flash::danger($e->getMessage());
//
//        return redirect(route('admin.update_state', $id));
//      }
//
//        $state = $this->locationRepo->getStateById($id);
//
//            foreach($state->cities as $city)
//            {
//               $areas = $city->areas();
//               $areas->delete();
//               $city->delete();
//            }
//        $state->delete();
//
//      Flash::success("State Deleted");

      return redirect()->route('admin.states');
    }

}
