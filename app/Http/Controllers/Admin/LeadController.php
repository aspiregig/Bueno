<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Career;
use App\Models\CaterLead;
use App\Models\BusinessQuery;
use App\Models\Query;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LeadController extends Controller
{   
    function __construct() 
    {
        $this->middleware('access.query',['only'=>['queries']]);
        $this->middleware('access.catering',['only'=>['catering']]);
        $this->middleware('access.career',['only'=>['career']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function career()
    {   
        $career_leads = Career::orderBy('created_at', "DESC")->get();
        $page = 'datatables';
        return view('admin.leads.career',compact('page','career_leads'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function catering()
    { 
        $cater_leads = CaterLead::orderBy('created_at', "DESC")->get();
        $page = 'datatables';
        return view('admin.leads.bulk_order',compact('page','cater_leads'));
    }

  public function viewCateringQuery($id)
  {
    $query = CaterLead::find($id);
    $page = 'datatables';
    return view('admin.leads.single_catering_query',compact('page','query'));
  }

  public function business()
  {
    $business_queries = BusinessQuery::orderBy('created_at', "DESC")->get();
    $page = 'datatables';
    return view('admin.leads.business_queries',compact('page','business_queries'));
  }

  public function viewBusinessQuery($id)
  {
    $query = BusinessQuery::find($id);
    $page = 'datatables';
    return view('admin.leads.single_business_query',compact('page','query'));
  }

    public function queries()
    { 
        $queries = Query::orderBy('created_at', "DESC")->get();
        $page = 'datatables';
        return view('admin.leads.queries',compact('page','queries'));
    }

    public function viewQuery($id)
    {
        $query = Query::find($id);
        $page = 'datatables';
        return view('admin.leads.query',compact('page','query'));
    }

}
