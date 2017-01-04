<?php

namespace App\Http\Controllers\Admin;

use Flash;
use Illuminate\Http\Request;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbCouponRepository as CouponRepo;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bueno\Validations\GoodyValidator;


class GoodyController extends Controller
{   
    protected $couponRepo,$goodyValidator;

    function __construct(CouponRepo $couponRepo,GoodyValidator $goodyValidator) 
    {
        $this->couponRepo = $couponRepo;
        $this->goodyValidator = $goodyValidator;
        $this->middleware('access.coupons');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $goodies = $this->couponRepo->getAllGoodies();
        $page = 'datatables';
        return view('admin.goody.goodies',compact('page','goodies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = 'datatables';
        return view('admin.goody.new_goody',compact('page'));
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
        $this->goodyValidator->fire($inputs);
      }
      catch(ValidationException $e)
      {
        return redirect(route('admin.new_goody'))->withErrors($e->getErrors())->withInput();
      }
        $this->couponRepo->newGoody($request);
      Flash::success('Goody Added Successfully');
        return redirect()->route('admin.goodies');
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
        $goody = $this->couponRepo->getGoodyById($id);
        $page = 'datatables';
        return view('admin.goody.update_goody',compact('page','goody'));
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
        $this->goodyValidator->fire($inputs);
      }
      catch(ValidationException $e)
      {
        return redirect(route('admin.new_goody'))->withErrors($e->getErrors())->withInput();
      }
        $this->couponRepo->updateGoody($id,$request);
      Flash::success('Goody Updated Successfully');
      return redirect()->route('admin.goodies');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
