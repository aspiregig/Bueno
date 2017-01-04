<?php

namespace App\Http\Controllers\Admin;

use Flash;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbUserRepository as UserRepo;
use Bueno\Validations\CreateGroupValidator as GroupValidator;
use Bueno\Repositories\DbPermissionRepository as PermissionRepo;


class GroupController extends Controller
{   
    protected $permissionRepo,$userRepo,$groupValidator;

    function __construct(PermissionRepo $permissionRepo,UserRepo $userRepo,GroupValidator $groupValidator) 
    {
        $this->permissionRepo = $permissionRepo;
        $this->userRepo = $userRepo;
        $this->groupValidator = $groupValidator;
        $this->middleware('access.group');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $groups = $this->permissionRepo->getAllGroups();
        $page = 'datatables';
        return view('admin.group.groups',compact('page','groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = 'datatables';
        return view('admin.group.new_group',compact('page'));
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
          $this->groupValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_group'))->withErrors($e->getErrors())->withInput();
        }
        Flash::success('New Group Added Successfully');
        $group = $this->permissionRepo->newGroup($inputs);
        return redirect()->route('admin.groups');
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
        $group = $this->permissionRepo->getGroupById($id);
        $page = 'datatables';
        return view('admin.group.update_group',compact('page','group'));
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
          $this->groupValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.update_group',$id))->withErrors($e->getErrors())->withInput();
        }
        Flash::success('Group Updated Successfully');
        $this->permissionRepo->updateGroupById($id,$request);
        return redirect()->route('admin.groups');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->permissionRepo->deleteGroupById($id))
        {
        Flash::success('Group Successfully Deleted.');
        return redirect()->route('admin.groups');
        }
        else
        {
        Flash::danger('Group Cannot be Deleted as Users are associated with it.');
        return redirect()->route('admin.update_group',$id);
        }
    }
}
