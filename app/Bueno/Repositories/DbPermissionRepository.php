<?php namespace Bueno\Repositories;

use App\Models\Group;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;

class DbPermissionRepository  {

  public function getAllGroups()
  {
    return Group::get();
  }

  public function getGroupById($id)
  {
    return Group::find($id);
  }

  public function newGroup($inputs)
  {
    $group =  Group::create($inputs);
    $role = new Role;
    $role->id = $group->id;
    $role->group_id = $group->id;
    $role->status = 1;
    $role->save();
    return $group;
  }

  public function updateGroupById($id,$inputs)
  {
    $group = Group::find($id);
    $group->name = $inputs->name;
    $group->save();
    return $group;
  }

  public function deleteGroupById($id)
  {
    $group = $this->getGroupById($id);
    if($group->total_associated_user==0 && !in_array($group->name, ["User","Admin","Kitchen Manager"]))
    {
      $this->deleteRoleById($id);
      $group->delete();
      return true;
    }
    else
      return false;
  }
  public function deleteRoleById($id)
  {
    $role = Role::find($id);
    $permissions = RolePermission::whereRoleId($role->id);
    $permissions->delete();
    $role->delete();
    return true;
  }

  public function updateRoleByGroupId($id,$inputs)
  {
     $role = Role::where('id',$id)->where('group_id',$id)->first();
     if($role==null)
     {
      $group =  Group::find($id);
      $role = new Role;
      $role->id = $group->id;
      $role->group_id = $group->id;
      $role->save();
     }

     $roles = RolePermission::whereRoleId($id)->delete();
     if($inputs!=null)
     {
      foreach ($inputs as $key => $value)
     {
        $permission = Permission::whereName($key)->first();
        RolePermission::create(array('role_id'=>$id,'permission_id'=>$permission->id));
     }
     }
  }

}