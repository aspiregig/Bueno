<?php

use App\Models\User;

function manageOrder(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='orders')
                return true;
        }
        return false;
}

function manageUser(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='users')
                return true;
        }
        return false;
}

function manageMeal(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='meals')
                return true;
        }
        return false;
}

function manageCoupon(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='coupons')
                return true;
        }
        return false;
}

function managelocation(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='locations')
                return true;
        }
        return false;
}

function manageAdText(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='ad_texts')
                return true;
        }
        return false;
}

function manageTestimonial(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='testimonials')
                return true;
        }
        return false;
}

function managePage(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='pages')
                return true;
        }
        return false;
}

function manageSettings(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='settings')
                return true;
        }
        return false;
}

function manageRole(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='roles')
                return true;
        }
        return false;
}

function manageGroup(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='groups')
                return true;
        }
        return false;
}

function manageKitchen(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='kitchens')
                return true;
        }
        return false;
}

function manageSlider(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='sliders')
                return true;
        }
        return false;
}

function manageCareer(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='careers')
                return true;
        }
        return false;
}

function manageCatering(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='catering')
                return true;
        }
        return false;
}

function manageQuery(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='queries')
                return true;
        }
        return false;
}

function manageDeliveryBoy(User $user)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='delivery_boys')
                return true;
        }
        return false;
}

function manage(User $user,$permission)
{
  $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name==$permission)
                return true;
        }
        return false;
}

function canManage(User $user, $permission)
{
    return in_array($permission, $user->group->role->permissions->pluck('name')->toArray());
}

function manageGeneral(User $user)
{
    if(manage($user,'dashboard')||manage($user,'reports')||manage($user,'orders')||manage($user,'users'))
        return true;
    else
        return false;
}


function myDefaulpage(User $user)
{   
        return redirect()->route('admin.home');
}