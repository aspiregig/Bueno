<?php

namespace App\Http\Controllers\Admin;

use Flash;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbMealRepository as MealRepo;
use Bueno\Repositories\ItemRepository as ItemRepo;
use Bueno\Validations\CreateComboValidator as ComboValidator;


class ComboController extends Controller
{
    protected $mealRepo,$comboValidator,$itemRepo;

    function __construct(MealRepo $mealRepo,ComboValidator $comboValidator,ItemRepo $itemRepo)
    {
        $this->mealRepo = $mealRepo;
        $this->itemRepo = $itemRepo;
        $this->comboValidator = $comboValidator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = $this->mealRepo->getAllItemsByType('App\Models\Combo');
        $page = 'datatables';
        return view('admin.combo.combos',compact('page','items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = 'form-product';
        $meals = $this->mealRepo->getAllMeals();
        $items = $this->mealRepo->getAllItems();
        $categories = $this->mealRepo->getAllCategories();
        $spice_levels = $this->itemRepo->getAllMealSpiceLevels();
        $meal_types = $this->itemRepo->getAllMealTypes();
        $availabilities = $this->mealRepo->getAllAvialablities();
        $cuisines = $this->mealRepo->getAllCuisines();

      if($meals == null)
        {
          Flash::info('Add Some Meal.');
          return redirect('admin.new_meal');
        }
        return view('admin.combo.new_combo',compact('page','meals','items','categories','spice_levels','meal_types','availabilities','cuisines'));
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
        if(!isset($inputs['is_hot_deal']))
        $request['is_hot_deal'] = '0';
        if(!isset($inputs['is_xprs']))
        $request['is_xprs'] = '0';
        if(!isset($inputs['is_sellable']))
        $request['is_sellable'] = '1';
        try
        {
          $this->comboValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_combo'))->withErrors($e->getErrors())->withInput();
        }
        Flash::success('Combo Created Successfully');
        $this->mealRepo->newCombo($request);
        return redirect()->route('admin.combos');
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
      $page = 'form-product';
      $meals = $this->mealRepo->getAllMeals();
      $categories = $this->mealRepo->getAllCategories();
      $spice_levels = $this->itemRepo->getAllMealSpiceLevels();
      $meal_types = $this->itemRepo->getAllMealTypes();
      $availabilities = $this->mealRepo->getAllAvialablities();
      $cuisines = $this->mealRepo->getAllCuisines();
      $items = $this->mealRepo->getAllItems();
      $item = $this->mealRepo->getItemById($id);
      $recommended_items = $item->recommendedItems->pluck('recommended_item_id')->toArray();
      return view('admin.combo.update_combo',compact('page','item','meals','items','recommended_items','categories','spice_levels','meal_types','availabilities','cuisines'));
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
        if(!isset($inputs['is_hot_deal']))
        $request['is_hot_deal'] = '0';
        if(!isset($inputs['is_xprs']))
        $request['is_xprs'] = '0';
        if(!isset($inputs['is_sellable']))
        $request['is_sellable'] = '1';
        try
        {
          $this->comboValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.update_combo',$id))->withErrors($e->getErrors())->withInput();
        }
        Flash::success('Combo Updated Successfully');
        $this->mealRepo->updateCombo($id,$request);
        return redirect()->route('admin.combos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $combo = $this->mealRepo->deleteComboById($id);
      if($combo)
        Flash::success('Combo Deleted Successfully');
      else
        Flash::danger('Combo Cannot be deleted');
        return redirect()->route('admin.combos');
    }
}
