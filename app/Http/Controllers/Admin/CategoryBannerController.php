<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Bueno\Repositories\DbCategoryBanner;
use App\Http\Requests;
use Bueno\Repositories\DbCategoryBannerRepository as BannerRepo;
use App\Http\Controllers\Controller;

class CategoryBannerController extends Controller
{   

    protected $bannerRepo;

    function __construct(BannerRepo $bannerRepo) 
    {
        $this->bannerRepo = $bannerRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $banners = $this->bannerRepo->getAllCategoryBanners();
        $page = 'datatables';
        return view('admin.categorybanner.category_banners',compact('page','banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $categories = $this->bannerRepo->getAllCategories();
        $page = 'datatables';
        return view('admin.categorybanner.new_category_banner',compact('page','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->bannerRepo->newBanner($request->all());
        return redirect()->route('admin.banners');
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
        $categories = $this->bannerRepo->getAllCategories();
        $banner = $this->bannerRepo->getBannerById($id);
        $page = 'datatables';
        return view('admin.categorybanner.update_category_banner',compact('page','categories','banner'));
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
        $this->bannerRepo->updateBanner($id,$request->all());
        return redirect()->route('admin.banners');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->bannerRepo->deleteBannerById($id);
        return redirect()->route('admin.banners');
        
    }

}
