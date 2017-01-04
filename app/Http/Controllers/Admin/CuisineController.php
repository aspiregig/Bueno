<?php

namespace App\Http\Controllers\Admin;

use Flash;
use Illuminate\Http\Request;
use Bueno\Repositories\DbCommonRepository as CommonRepo;
use App\Http\Requests;
use Bueno\Validations\ValidationException;
use App\Http\Controllers\Controller;
use Bueno\Validations\CuisineValidator;


class CuisineController extends Controller
{
  protected $commonRepo,$cuisineValidator;

  function __construct(CommonRepo $commonRepo,CuisineValidator $cuisineValidator)
  {
    $this->commonRepo = $commonRepo;
    $this->cuisineValidator = $cuisineValidator;
    $this->middleware('access.meal');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $cuisines = $this->commonRepo->getAllcuisines();
    $page = 'datatables';
    return view('admin.cuisine.cuisines',compact('page','cuisines'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $page = 'datatables';
    return view('admin.cuisine.new_cuisine',compact('page'));
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
      $this->cuisineValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('admin.new_cuisine'))->withErrors($e->getErrors())->withInput();
    }
    Flash::success('New Cuisine Added Successfully');
    $cuisine = $this->commonRepo->newcuisine($inputs);
    return redirect()->route('admin.cuisines');
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
    $cuisine = $this->commonRepo->getcuisineById($id);
    $page = 'datatables';
    return view('admin.cuisine.update_cuisine',compact('page','cuisine'));
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
      $this->cuisineValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('admin.update_cuisine',$id))->withErrors($e->getErrors())->withInput();
    }
    Flash::success('Cuisine Updated Successfully');
    $this->commonRepo->updatecuisine($id,$inputs);
    return redirect()->route('admin.cuisines');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    if($this->commonRepo->deletecuisine($id))
    {Flash::success('Cuisine deleted successfully');
      return redirect()->route('admin.cuisines');}
    else
    {
      Flash::danger('Cuisine cannot be deleted');
      return redirect()->route('admin.update_cuisine',$id)->withInput();
    }
  }
}
