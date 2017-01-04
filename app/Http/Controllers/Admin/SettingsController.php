<?php

namespace App\Http\Controllers\Admin;

use Flash;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbSettingRepository as SettingRepo;
use Bueno\Validations\UpdateSettingValidator as SettingValidator;
use Bueno\Validations\UpdateBuenoSettingValidator as BuenoValidator;

class SettingsController extends Controller
{
    /**
    * @var SettingValidator
    * @var SettingRepo
    */
    protected $settingValidator,$settingRepo,$buenoValidator;

    /**
    * @param SettingtValidator $settingValidator
    * @param SettingRepo $settingRepo
    */
    function __construct(SettingRepo $settingRepo,SettingValidator $settingValidator,BuenoValidator $buenoValidator)
    {
      $this->middleware('access.setting');
      $this->settingRepo = $settingRepo;
      $this->buenoValidator = $buenoValidator;
      $this->settingValidator = $settingValidator;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {   
        $setting = $this->settingRepo->getAllSettings();
        $setting['master_switch'] = $this->settingRepo->getMasterSwitch();
        $payment_mode = $this->settingRepo->getPaymentModes();
        $days = $this->settingRepo->getAllDays();
        $timings = $this->settingRepo->getAllTimings();
        $timings = $timings ?  $timings : [];

        $page = 'datatables';
        return view('admin.settings.settings',compact('page','setting','days', 'timings','payment_mode'));
    }

    public function buenoSettings()
    {   
        $settings = $this->settingRepo->getAllBuenoSettings();
        $page = 'datatables';
        return view('admin.settings.bueno_points',compact('page','settings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_settings(Request $request)
    {
        $inputs = $request->all();
         try
        {
          $this->settingValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.settings'))->withErrors($e->getErrors())->withInput();
        }
        if(!(isset($inputs['payment_mode'])))
        {
          Flash::danger('Atleast One Payment Mode must be Active.');
          return redirect(route('admin.settings'))->withInput();
       }
       else
       {
          $this->settingRepo->update($inputs);
       }

      Flash::success('Successfully Updated!');
      return redirect()->route('admin.settings');
    }

    public function buenoSettingsUpdate(Request $request)
    {
      $inputs = $request->all();

      try
        {
          $this->buenoValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.buenosettings'))->withErrors($e->getErrors())->withInput();
        }
      $this->settingRepo->updateBuenoPoint($request);
      Flash::success('Settings Updated Successfully');
      return redirect()->route('admin.buenosettings');
    }

}
