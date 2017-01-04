<?php

namespace App\Http\Controllers\Admin;

use Bueno\Validations\ValidationException;
use Illuminate\Http\Request;
use Flash;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bueno\Repositories\DbCommonRepository as CommonRepo;
use Bueno\Validations\CategoryValidator;

class CategoryController extends Controller
{

  protected $commonRepo,$categoryValidator;

  function __construct(CommonRepo $commonRepo,CategoryValidator $categoryValidator)
  {
    $this->commonRepo = $commonRepo;
    $this->categoryValidator = $categoryValidator;
    $this->middleware('access.meal');
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $categories = $this->commonRepo->getAllCategories();
      $page = 'datatables';
      return view('admin.category.categories',compact('page','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $page = 'datatables';
      return view('admin.category.new_category',compact('page'));
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
        $this->categoryValidator->fire($inputs);
      }
      catch(ValidationException $e)
      {
        return redirect(route('admin.new_category'))->withErrors($e->getErrors())->withInput();
      }
      Flash::success('New Category Added Successfully');
      $category = $this->commonRepo->newCategory($inputs);
      return redirect()->route('admin.categories');
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
      $category = $this->commonRepo->getCategoryById($id);
      $page = 'datatables';
      return view('admin.category.update_category',compact('page','category'));
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
        $this->categoryValidator->fire($inputs);
      }
      catch(ValidationException $e)
      {
        return redirect(route('admin.update_category',$id))->withErrors($e->getErrors())->withInput();
      }
      Flash::success('Category Updated Successfully');
      $this->commonRepo->updateCategory($id,$inputs);
      return redirect()->route('admin.categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      if($this->commonRepo->deleteCategory($id))
      {Flash::success('Category deleted successfully');
      return redirect()->route('admin.categories');}
      else
      {
        Flash::danger('Category cannot be deleted');
        return redirect()->route('admin.update_category',$id)->withInput();
      }
    }
}
