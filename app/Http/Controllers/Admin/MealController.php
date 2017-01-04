<?php

namespace App\Http\Controllers\Admin;

use App\Events\MealWasDisabled;
use Flash;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbMealRepository as MealRepo;
use Bueno\Repositories\ItemRepository as ItemRepo;
use Bueno\Validations\CreateMealValidator as MealValidator;


class MealController extends Controller
{

    protected $mealRepo,$mealValidator,$itemRepo;

    function __construct(MealRepo $mealRepo,MealValidator $mealValidator,ItemRepo $itemRepo)
    {
        $this->mealRepo = $mealRepo;
        $this->mealValidator = $mealValidator;
        $this->itemRepo = $itemRepo;
        $this->middleware('access.meal');
    }

    /**
     * Display a listing of the meal.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $items = $this->mealRepo->getAllItemsByType('App\Models\Meal');
        $page = 'datatables';
        return view('admin.meal.meals',compact('page','items'));
    }

    /**
     * Show the form for creating a new meal.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   $cuisines = $this->mealRepo->getAllCuisines();
        $categories = $this->mealRepo->getAllCategories();
        $meal_types = $this->itemRepo->getAllMealTypes();
        $availabilities = $this->mealRepo->getAllAvialablities();
        $spice_levels = $this->itemRepo->getAllMealSpiceLevels();
        $items = $this->mealRepo->getAllItems();
        $page = 'datatables';
        return view('admin.meal.new_meal',compact('page','cuisines','categories','availabilities','items','meal_types','spice_levels'));
    }

    /**
     * Store a newly created meal in storage.
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
          $this->mealValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_meal'))->withErrors($e->getErrors())->withInput();
        }

        $item = $this->mealRepo->newMeal($request);
        
        Flash::success('Meal Successfully Created');

        return redirect()->route('admin.meals');
    }

    /**
     * Show the form for editing the specified meal.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $cuisines = $this->mealRepo->getAllCuisines();
        $items = $this->mealRepo->getAllItems();
        $categories = $this->mealRepo->getAllCategories();
        $spice_levels = $this->itemRepo->getAllMealSpiceLevels();
        $meal_types = $this->itemRepo->getAllMealTypes();
        $availabilities = $this->mealRepo->getAllAvialablities();
        $item = $this->mealRepo->getItemById($id);
        $mealavailable = $item->availabilities->pluck('id')->toArray();
        $recommended_items = $item->recommendedItems->pluck('recommended_item_id')->toArray();
        $page = 'form-product';
        return view('admin.meal.update_meal',compact('page','item','cuisines','categories','availabilities','mealavailable','items','recommended_items','meal_types','spice_levels'));
    }

    /**
     * Update the specified meal in storage.
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

        $items = $this->mealRepo->getAllItems();

        try
        {
          $this->mealValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.update_meal',$id))->withErrors($e->getErrors())->withInput();
        }

        $item = $this->mealRepo->updateMeal($id,$request);

        if($item->itemable->statu!=1)
        event(new MealWasDisabled($item->itemable));

        Flash::success('Meal Successfully Updated');

        return redirect()->route('admin.meals');
        
    }

    /**
     * Remove the specified meal from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->mealRepo->deleteMealById($id)){
        Flash::success('Meal Successfully Deleted');
        return redirect()->route('admin.meals');
        }
        else
        {
            Flash::danger('Meal Cannot be Deleted.');
            return redirect()->route('admin.update_meal',$id);
        }
    }
}
