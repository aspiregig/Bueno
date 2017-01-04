<?php

namespace App\Http\Controllers\Admin;

use Flash;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\DeliveryBoyWasCreated;
use App\Events\DeliveryBoyWasUpdated;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbKitchenRepository as KitchenRepo;
use Bueno\Validations\CreateDeliveryBoyValidator as DeliveryBoyValidator;


class DeliveryBoyController extends Controller
{ 

     protected $kitchenRepo;
     protected $deliveryBoyValidator;

    function __construct(KitchenRepo $kitchenRepo,DeliveryBoyValidator $deliveryBoyValidator) 
    {
        $this->kitchenRepo = $kitchenRepo;
        $this->deliveryBoyValidator = $deliveryBoyValidator;
        $this->middleware('access.deliveryBoy');
    }

    /**
     * Display a listing of the delivery boys.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
      $user = auth()->user();

      if($user->group->name=="Kitchen Manager")
      {
        $delivery_boys = $user->kitchen->first()->delivery_boys;
      }
      else
      {
        $delivery_boys = $this->kitchenRepo->getAllDeliveryBoys();
      }

        $page = 'datatables';
        return view('admin.deliveryboy.delivery_boys',compact('page','delivery_boys'));
    }

    /**
     * Show the form for creating a new delivery boy.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $user = auth()->user();


      if($user->group->name=="Kitchen Manager")
      {
        $kitchens = $user->kitchen;
      }
      else
      {
        $kitchens = $this->kitchenRepo->getAllKitchens();
      }
      if($kitchens->count()==0)
      {
        Flash::info('Add a kitchen to Add a Delivery boy/Place an Order');
          return redirect()->route('admin.new_kitchen');
      }
        $page = 'form';
        return view('admin.deliveryboy.new_delivery_boy',compact('page','kitchens'));
    }

    /**
     * Store a newly created delivery boy in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();

        try
        {
          $this->deliveryBoyValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_delivery_boy'))->withErrors($e->getErrors())->withInput();
        }

      $delivery_boy = $this->kitchenRepo->newDeliveryBoy($inputs);

      Flash::success('Delivery Boy Successfully Created');
      return redirect()->route('admin.delivery_boys');
    }

    /**
     * Display the specified delivery boy.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified delivery boy.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $user = auth()->user();

      if($user->group->name=="Kitchen Manager")
      {
        $kitchens = $user->kitchen;
      }
      else
      {
        $kitchens = $this->kitchenRepo->getAllKitchens();
      }
        $delivery_boy = $this->kitchenRepo->getDeliveryBoyById($id);
        $page = 'form';
        return view('admin.deliveryboy.update_delivery_boy',compact('page','delivery_boy','kitchens'));
    }

    /**
     * Update the specified delivery boy in storage.
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
          $this->deliveryBoyValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.update_delivery_boy',$id))->withErrors($e->getErrors())->withInput();
        }
        Flash::success('Successfully Updated.');
        $delivery_boy = $this->kitchenRepo->updateDeliveryBoyById($id,$inputs);
        return redirect()->route('admin.delivery_boys');
    }

    public function destroy($id)
    {
        if($this->kitchenRepo->deleteDeliveryBoy($id))
        {
          Flash::success('Delivery Boy Deleted');
          return redirect()->route('admin.delivery_boys');
        }
      else
        {
          Flash::danger('Delivery Boy cannot be deleted.');
          return redirect()->route('admin.update_delivery_boy',$id);
        }
    }

    
}
