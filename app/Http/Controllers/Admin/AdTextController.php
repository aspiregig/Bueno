<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Bueno\Notifications\Flash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbAdRepository as AdRepo;
use Bueno\Validations\CreateAdTextValidator as AdTextValidator;

class AdTextController extends Controller
{
    /**
    * @var AdTextValidator
    * @var AdRepo
    */
    protected $adRepo,$adTextValidator;

    /**
    * @param AdTextValidator $adTextValidator
    * @param Adrepository $adRepo
    */
    function __construct(AdRepo $adRepo,AdTextValidator $adTextValidator)
    {
        $this->adRepo = $adRepo;
        $this->adTextValidator = $adTextValidator;
        $this->middleware('access.adText');
    }

    /**
     * Display a listing of the AdTexts.
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {   
        $ad_texts = $this->adRepo->getAllAdText();
        $page = 'datatables';
        return view('admin.adtext.ad_texts',compact('page','ad_texts'));
    }

    /**
     * Show the form for creating a new Ad Text.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page = 'datatables';
        return view('admin.adtext.new_ad_text',compact('page'));
    }

    /**
     * Validate and create an Ad Text
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        try
        {
          $this->adTextValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_ad_text'))->withErrors($e->getErrors())->withInput();
        }
        $ad_text = $this->adRepo->newAdText($inputs);

        Flash::success('Ad Text Successfully Created');
        return redirect()->route('admin.ad_texts');
    }

    /**
     * Show the form for editing the specified Ad Text.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {   
        $banner = $this->adRepo->getAdTextById($id);
        $page = 'datatables';
        return view('admin.adtext.update_ad_text',compact('page','banner'));
    }

    /**
     * Validate and Update the specified AdText in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
      $inputs = $request->all();
      try
      {
       $this->adTextValidator->fire($inputs);
      }
      catch(ValidationException $e)
      {
        return redirect(route('admin.update_ad_text'))->withErrors($e->getErrors())->withInput();
      }

      $ad_text = $this->adRepo->updateAdTextById($id,$inputs);

      Flash::success('Successfully Updated');
      return redirect()->route('admin.ad_texts');
    }

    /**
     * Remove the specified Ad Text from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $deleted = $this->adRepo->deleteAdTextById($id);
        if($deleted)
        {
          Flash::success('Ad Successfully deleted');
          return redirect()->route('admin.ad_texts');
        }
      else
      {
        Flash::danger('Ad Cannot be deleted as it is active');
        return redirect()->route('admin.update_ad_text',$id);
      }


    }
}
