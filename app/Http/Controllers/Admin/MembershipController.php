<?php

namespace App\Http\Controllers\Admin;

use Bueno\Validations\ValidationException;
use Illuminate\Http\Request;
use Flash;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bueno\Repositories\DbMembershipRepository as MembershipRepo;
use Bueno\Validations\CreateMembershipValidator as MembershipValidator;

class MembershipController extends Controller
{
  protected $membershipRepo,$membershipValidator;

  function __construct(MembershipRepo $membershipRepo,MembershipValidator $membershipValidator)
  {
    $this->membershipRepo = $membershipRepo;
    $this->membershipValidator = $membershipValidator;
    $this->middleware('access.membership');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $memberships = $this->membershipRepo->getAllMemberships();
    $page = 'datatables';
    return view('admin.membership.memberships',compact('page','memberships'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $page = 'datatables';
    return view('admin.membership.new_membership',compact('page'));
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
      $this->membershipValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('admin.new_membership'))->withErrors($e->getErrors())->withInput();
    }
    Flash::success('New Membership Added Successfully');
    $membership = $this->membershipRepo->newMembership($inputs);
    return redirect()->route('admin.update_membership',$membership->id);
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
    $membership = $this->membershipRepo->getMembershipById($id);
    $page = 'datatables';
    return view('admin.membership.update_membership',compact('page','membership'));
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
      $this->membershipValidator->fire($inputs);
    }
    catch(ValidationException $e)
    {
      return redirect(route('admin.update_membership',$id))->withErrors($e->getErrors())->withInput();
    }
    Flash::success('Membership Updated Successfully');
    $this->membershipRepo->updateMembershipById($id,$inputs);
    return redirect()->route('admin.memberships',$id);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    if($this->membershipRepo->deleteMembershipById($id))
    {
      Flash::success('Membership Successfully Deleted.');
      return redirect()->route('admin.memberships');
    }
    else
    {
      Flash::danger('Membership Cannot be Deleted as Users are associated with it.');
      return redirect()->route('admin.update_membership',$id);
    }
  }
}
