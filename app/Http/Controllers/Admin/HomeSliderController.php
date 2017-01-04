<?php

namespace App\Http\Controllers\Admin;

use Flash;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Validations\CreateSliderValidator as SliderValidator;
use Bueno\Repositories\DbHomeSliderRepository as HomeSliderRepo;



class HomeSliderController extends Controller
{
   protected $sliderRepo,$sliderValidator;

  function __construct(HomeSliderRepo $sliderRepo,SliderValidator $sliderValidator) 
  {
    $this->sliderRepo = $sliderRepo;
    $this->sliderValidator = $sliderValidator;
    $this->middleware('access.slider');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $sliders  = $this->sliderRepo->getAllHomeSliders();
    $page = 'datatables';
    return view('admin.slider.sliders',compact('page','sliders'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $page = 'datatables';
    return view('admin.slider.new_slider',compact('page'));
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
      $this->sliderValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('admin.new_home_slider'))->withErrors($e->getErrors())->withInput();
    }

    $slider = $this->sliderRepo->newHomeSlider($request);

    Flash::success('Home Slider Successfully created.');
    
    return redirect()->route('admin.home_sliders');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $slider = $this->sliderRepo->getSliderById($id); 
    $page = 'datatables';
    return view('admin.slider.update_slider',compact('page','slider'));
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
    $this->sliderValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
    return redirect(route('admin.update_home_slider',$id))->withErrors($e->getErrors())->withInput();
    }

    $slider = $this->sliderRepo->updateSlider($id,$request);

    Flash::success('Home Slider Successfully updated.');
    
    return redirect()->route('admin.home_sliders');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    if($this->sliderRepo->deleteSlider($id))
    {
      Flash::success('Slider Successfully deleted');
      return redirect()->route('admin.home_sliders');
    }
    else
    {
      Flash::danger('Slider Cannot be deleted as it set to Active');
      return redirect()->route('admin.update_home_slider',$id);
    }
  }
}
