<?php

namespace App\Http\Controllers\Admin;

use Flash;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbUserRepository as UserRepo;
use Bueno\Repositories\DbKitchenRepository as KitchenRepo;
use Bueno\Repositories\DbLocationRepository as LocationRepo;
use Bueno\Validations\CreateKitchenValidator as KitchenValidator;

class KitchenController extends Controller
{

    protected $kitchenRepo,$locationRepo,$userRepo,$kitchenValidator;

    function __construct(KitchenRepo $kitchenRepo,LocationRepo $locationRepo,UserRepo $userRepo,KitchenValidator $kitchenValidator) 
    {
        $this->kitchenRepo = $kitchenRepo;
        $this->locationRepo = $locationRepo;
        $this->userRepo = $userRepo;
        $this->kitchenValidator = $kitchenValidator;
        $this->middleware('access.kitchen');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $kitchens = $this->kitchenRepo->getAllKitchens();
        $page = 'datatables';
        return view('admin.kitchen.kitchens',compact('page','kitchens'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $areas = $this->locationRepo->getNotAssignedAreas();

        if($areas->count()==0)
        {
          Flash::info('You have to first add an area in order to create a kitchen');

          return redirect()->route('admin.new_area');
        }
        $users = $this->userRepo->getNotAssignedManagers();
        if($users->count()==0)
        {
          Flash::info('You have to first create a manager in order to create a kitchen !');

          return redirect()->route('admin.users');
        }
        $page = 'datatables';
        return view('admin.kitchen.new_kitchen',compact('page','areas','users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $inputs = $request->all();
        try
        {
          $this->kitchenValidator->fire($inputs);

        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_kitchen'))->withErrors($e->getErrors())->withInput();
        }

        $this->kitchenRepo->newKitchen($request);

        Flash::success('Kitchen Successfully Created');

        return redirect()->route('admin.kitchens');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $page = 'datatables';
        $availableManagers = $this->userRepo->getNotAssignedManagers();
        $kitchen = $this->kitchenRepo->getKitchenById($id);
        $assignedAreas = $this->locationRepo->getNotAssignedAreas();
        $kitchenAreas = $kitchen->areas;
        $areas = $assignedAreas->merge($kitchenAreas);
        $assignedMangers = $kitchen->managers;
        $assignedMangersIds = $assignedMangers->pluck('id')->toArray();
        $users = $availableManagers->merge($assignedMangers);

      return view('admin.kitchen.update_kitchen',compact('page','areas','users','kitchen','assignedMangersIds'));
    }

    /**
     * Update the specified resource in storage.
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
          $this->kitchenValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.update_kitchen',$id))->withErrors($e->getErrors())->withInput();
        }
        
        $kitchen = $this->kitchenRepo->updateKitchen($request,$id);

        Flash::success('Kitchen Successfully Updated');

        return redirect()->route('admin.kitchens');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->kitchenRepo->deleteKitchenById($id))
      {
        Flash::success('Kitchen Deleted Successfully.');
        return redirect()->route('admin.kitchens');
      }
      else
      {
        Flash::danger('Kitchen cannot be delete.');
        return redirect()->route('admin.update_kitchen',$id);
      }
    }

}
