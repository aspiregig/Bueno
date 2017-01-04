<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Bueno\Validations\ValidationException;
use Flash;
use Bueno\Validations\AvailabilityValidator;
use Bueno\Repositories\DbCommonRepository as CommonRepo;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AvailabilityController extends Controller
{
  protected $commonRepo,$availabilityValidator;

  function __construct(CommonRepo $commonRepo,AvailabilityValidator $availabilityValidator)
  {
    $this->commonRepo = $commonRepo;
    $this->availabilityValidator = $availabilityValidator;
    $this->middleware('access.meal');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $availabilities = $this->commonRepo->getAllAvailabilities();
    $page = 'datatables';
    return view('admin.availability.availabilities',compact('page','availabilities'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $page = 'datatables';
    return view('admin.availability.new_availability',compact('page'));
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
      $this->availabilityValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('admin.new_availability'))->withErrors($e->getErrors())->withInput();
    }
    Flash::success('New Availability Added Successfully');
    $availability = $this->commonRepo->newAvailability($inputs);
    return redirect()->route('admin.availabilities');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {

  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $availability = $this->commonRepo->getAvailabilityById($id);
    $page = 'datatables';
    return view('admin.availability.update_availability',compact('page','availability'));
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
      $this->availabilityValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('admin.update_availability',$id))->withErrors($e->getErrors())->withInput();
    }
    Flash::success('availability Updated Successfully');
    $this->commonRepo->updateAvailability($id,$inputs);
    return redirect()->route('admin.availabilities');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    if($this->commonRepo->deleteavailability($id))
    {Flash::success('availability deleted successfully');
      return redirect()->route('admin.availabilities');}
    else
    {
      Flash::danger('availability cannot be deleted');
      return redirect()->route('admin.update_availability',$id)->withInput();
    }
  }
}
