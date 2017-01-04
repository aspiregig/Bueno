<?php

namespace App\Http\Controllers\Admin;

use Flash;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbPageRepository as PageRepo;
use Bueno\Validations\CreatePageValidator as PageValidator;

class PageController extends Controller
{
  protected $pageRepo,$pageValidator;

    function __construct(PageRepo $pageRepo,PageValidator $pageValidator) 
    {
        $this->pageRepo = $pageRepo;
        $this->pageValidator = $pageValidator;
        $this->middleware('access.pages');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $pages = $this->pageRepo->getAllPages();
        $page = 'datatables';
        return view('admin.seo.seo_titles',compact('page','pages'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = 'datatables';
        return view('admin.seo.new_seo_title',compact('page'));
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
          $this->pageValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_seo_title'))->withErrors($e->getErrors())->withInput();
        }

        $web_page = $this->pageRepo->newPage($request);
        Flash::success('SEO settings successfully created');
        return redirect()->route('admin.seo_titles');
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
        $pages = $this->pageRepo->getAllPages();
        $web_page = $this->pageRepo->getPageById($id); 
        $page = 'datatables';
        return view('admin.seo.update_seo_title',compact('page','web_page','pages'));
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
        $this->pageRepo->updatePage($id,$request);
        return redirect()->route('admin.seo_titles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $web_page = $this->pageRepo->getPageById($id); 
        $web_page->delete();
        Flash::success('Seo Settings Successfully Deleted');
        return redirect()->route('admin.seo_titles');
    }
}
