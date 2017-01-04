<?php

namespace App\Http\Controllers\Admin;

use Flash;
use App\Models\Ngo;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbNgoRepository as NgoRepo;
use Bueno\Validations\CreateNgoValidator as NgoValidator;

class NgoController extends Controller
{
    /**
    * @var NgoValidator
    * @var NgoRepo
    */
    protected $ngoValidator,$ngoRepo;


    /**
    * @param StateValidator $stateValidator
    * @param LocationRepo $locationRepo
    */
    function __construct(NgoValidator $ngoValidator,NgoRepo $ngoRepo)
    {
      $this->ngoValidator = $ngoValidator;
      $this->ngoRepo = $ngoRepo;
      $this->middleware('access.ngo');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ngos = $this->ngoRepo->getAllNgos();
        $page = 'datatables';
        return view('admin.ngo.ngos',compact('ngos','page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = 'datatables';
        return view('admin.ngo.new_ngo',compact('page'));
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
          $this->ngoValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_ngo'))->withErrors($e->getErrors())->withInput();
        }

        $ngo = $this->ngoRepo->newNgo($request);

        Flash::success('Ngo Successfully created.');

        return redirect()->route('admin.ngos');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ngo = Ngo::find($id);
        $page = 'datatables';
        return view('admin.ngo.update_ngo',compact('ngo','page'));
    }

    /**
     * Update the specified ngo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try
        {
          $this->ngoValidator->fire($request->all());
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.update_ngo',$id))->withErrors($e->getErrors())->withInput();
        }

       $ngo = $this->ngoRepo->updateNgo($request,$id);

        Flash::success('Successfully Updated');
        
        return redirect()->route('admin.ngos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->ngoRepo->deleteNgoById($id)){
            Flash::success('Ngo Successfully deleted');
            return redirect()->route('admin.ngos');
        }
        else
        {
            Flash::danger('Ngo Cannot be deleted');
        return redirect()->route('admin.update_ngo',$id);
        }
    }
}
