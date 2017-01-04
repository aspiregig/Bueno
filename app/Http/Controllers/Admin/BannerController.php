<?php

namespace App\Http\Controllers\Admin;

use App\Models\Header;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Validations\CreateBannerValidator as BannerValidator;

class BannerController extends Controller
{

  protected $bannerValidator;

  function __construct(BannerValidator $bannerValidator)
  {
    $this->bannerValidator = $bannerValidator;
    $this->middleware('access.banner');
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $headers = Header::get();
        $page = 'datatables';
        return view('admin.banner.ad_texts',compact('page','headers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = 'datatables';
        return view('admin.banner.new_ad_text',compact('page'));
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
        $this->bannerValidator->fire($inputs);
      }
      catch(ValidationException $e)
      {
        return redirect(route('admin.new_html_banner'))->withErrors($e->getErrors())->withInput();
      }

        $header = Header::create($request->all());

        $file = $request->file('display_pic_url');

        if($file!=null) {
          $filename = upload_file($file, 'banners');

          $header->display_image_url = $filename;

          $header->display_image_mime = $file->getClientMimeType();

        }

      $header->save();

      return redirect()->route('admin.html_banners');
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
        $header = Header::find($id);
        $page = 'datatables';
        return view('admin.banner.update_ad_text',compact('page','header'));
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
        $banner = Header::find($id);

        $inputs = $request->all();

        try
        {
          $this->bannerValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.update_html_banner',$id))->withErrors($e->getErrors())->withInput();
        }

        $banner->fill($inputs)->save();

      $file = $request->file('display_pic_url');

      if($file!=null) {

        $filename = upload_file($file, 'banners');

        $banner->display_image_url = $filename;

        $banner->display_image_mime = $file->getClientMimeType();

      }

      $banner->save();

        return redirect()->route('admin.html_banners');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $header = Header::findorFail($id);
        $header->delete();
        return redirect()->route('admin.html_banners');
    }
}
