<?php

namespace App\Http\Controllers\Admin;

use Flash;
use App\Http\Requests;
use Bueno\Repositories\DbMealRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbUserRepository as UserRepo;
use Bueno\Repositories\DbKitchenRepository as KitchenRepo;
use Bueno\Repositories\DbLocationRepository as LocationRepo;
use Bueno\Validations\UpdateStockValidator as StockValidator;

class StocksController extends Controller
{

  protected $kitchenRepo,$locationRepo,$userRepo,$stockValidator,$mealRepo;

  function __construct(KitchenRepo $kitchenRepo,LocationRepo $locationRepo,UserRepo $userRepo,StockValidator $stockValidator, DbMealRepository $mealRepo)
  {
    $this->kitchenRepo = $kitchenRepo;
    $this->stockValidator = $stockValidator;
    $this->mealRepo = $mealRepo;
    $this->middleware('access.stock');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $stocks = $this->mealRepo->getAllStocks();

    $items = $this->mealRepo->getAllActiveItems();

    $user = auth()->user();

    if($user->group->name=="Kitchen Manager")
        { 
          if(auth()->user()->kitchen->count()!=0)
            $kitchens = $user->kitchen;
          else
            $kitchens = [];
         } 
         else
      $kitchens  = $this->kitchenRepo->getAllKitchens();

    $page = 'datatables';

    return view('admin.stocks.index',compact('page','stocks','kitchens','items'));
  }

  public function edit($id)
  {
    $page = 'datatables';

    $stock = $this->mealRepo->getStockById($id);

    return view('admin.stocks.update_stock',compact('page','stock'));
  }

  public function update($id)
  {
    $inputs = request()->all();

    try
    {
      $this->stockValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('admin.stocks.edit',$id))->withErrors($e->getErrors())->withInput();
    }

    $stock = $this->mealRepo->updateStockById($id, $inputs);

    Flash::success('Stock Updated');

    return redirect(route('admin.stocks.edit', $id));
  }


}
