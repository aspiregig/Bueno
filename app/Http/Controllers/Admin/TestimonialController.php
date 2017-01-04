<?php

namespace App\Http\Controllers\Admin;

use Flash;
use App\Http\Requests;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Validations\CreateTestimonialValidator as TestimonialValidator;

class TestimonialController extends Controller
{

    protected $testiValidator;

    function __construct(TestimonialValidator $testiValidator)
    {
        $this->middleware('access.testimonials');
        $this->testiValidator = $testiValidator;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
         $testimonials = Testimonial::get();
        $page = 'datatables';
        return view('admin.testimonial.testimonials',compact('page','testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
       
        $page = 'datatables';
        return view('admin.testimonial.new_testimonial',compact('page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try
      {
        $this->testiValidator->fire($request->all());
      }
      catch(ValidationException $e)
      {
        return redirect(route('admin.new_testimonial'))->withErrors($e->getErrors())->withInput();
      }
        $testi = Testimonial::create($request->all());
        Flash::success('Testimonial created Successfully');
        return redirect()->route('admin.update_testimonial',$testi->id);
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
        $testi = Testimonial::find($id);
        $page = 'datatables';
        return view('admin.testimonial.update_testimonial',compact('page','testi'));
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
        $testi = Testimonial::findorFail($id);
        $input = $request->all();
        $testi->fill($input)->save();
        Flash::success('Successfully Updated');
        return redirect()->route('admin.update_testimonial',$id); 
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $testi = Testimonial::findorFail($id);
        $testi->delete();
        Flash::success('Testimonial Deleted Successfully');
        return redirect()->route('admin.testimonials'); 
    }
}
