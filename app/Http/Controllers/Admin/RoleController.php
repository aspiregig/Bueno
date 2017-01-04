<?php

namespace App\Http\Controllers\Admin;

use Bueno\Notifications\Flash;
use Illuminate\Http\Request;
use Bueno\Repositories\DbPermissionRepository as PermissionRepo;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    protected $permissionRepo;

    function __construct(PermissionRepo $permissionRepo) 
    {
        $this->permissionRepo = $permissionRepo;
        $this->middleware('access.role');
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
        return view('admin.role.roles',compact('page','groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = 'datatables';
        return view('admin.role.new_role',compact('page'));
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
        $permissions[] = null;
        if($group->role!=null)
        {
        $role_permissions = $group->role->role_permissions;
        $permissionsCount = 0;
        foreach ($role_permissions as $role_permission) {
          $permissions[$permissionsCount++] =  $role_permission->permission->name;
        }
    }
        $page = 'datatables';
        return view('admin.role.update_role',compact('page','group','permissions'));
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
        if($id == 1)
        {
        Flash::danger('Admins Roles Cannot be Updated.');
        return redirect()->route('admin.roles');
        }
        else
        {
        $this->permissionRepo->updateRoleByGroupId($id,$request->get('permissions'));
        Flash::success('Role Access Successfully Updated.');
        return redirect()->route('admin.roles');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
