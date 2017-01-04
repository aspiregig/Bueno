<?php

namespace App\Http\Controllers\Admin;

use Bueno\Validations\PressValidator;
use Flash;
use Bueno\Repositories\DbCommonRepository;
use Bueno\Validations\ValidationException;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PressController extends Controller
{
  protected $membershipRepo,$membershipValidator;

  function __construct(DbCommonRepository $commonRepo, PressValidator $validator)
  {
    $this->commonRepo = $commonRepo;

    $this->validator = $validator;
  }


  public function index()
  {
      $press = $this->commonRepo->getAllPress();

      $page = 'datatables';

    return view('admin.press.index',compact('page','press'));
  }

  public function create()
  {
    $page = 'datatables';

    return view('admin.press.new_press',compact('page','press'));
  }

  public function store()
  {
    $inputs = request()->all();

    try
    {
      $this->validator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect()->back()->withErrors($e->getErrors())->withInput();
    }

    Flash::success('New Press Added Successfully');

    $press = $this->commonRepo->newPress($inputs);

    return redirect()->route('admin.update_press',$press->id);
  }

  public function edit($press_id)
  {
    $press = $this->commonRepo->getPressById($press_id);

    $page = 'datatables';

    return view('admin.press.update_press',compact('page','press'));
  }

  public function update($press_id)
  {
    $inputs = request()->all();

    try
    {
      $this->validator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect()->back()->withErrors($e->getErrors())->withInput();
    }

    $press = $this->commonRepo->updatePress($press_id, $inputs);

    Flash::success('Press Updated');

    $page = 'datatables';

    return redirect()->back();
  }

  public function destroy($press_id)
  {
    $this->commonRepo->deletePress($press_id);

    return redirect(route('admin.press'));
  }
}
