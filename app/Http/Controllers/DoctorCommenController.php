<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DoctorCommenController extends Controller
{
    public static function allDoctorPermissions()
    {
        return Permission::whereIn('guard_name', ['doctor'])->get(['id', 'name', 'guard_name']);;
    }

    public static function getDoctorRolePermission($role_id)
    {
        if (!empty($role_id)) {
            $role = Role::find($role_id);   
            return $role->permissions();
        } else {
            return null;
        }
    }

    public static function showDoctorRolePermission($role_id, $group_type, $type = '')
    {
        #get all permission
        $allPermissionsLists  = self::allDoctorPermissions($group_type, $type);

        #get role permission ids
        if ($role_id) {
            $rolePermissions    = self::getDoctorRolePermission($role_id);
            $rolePermissions    = !empty($rolePermissions) ? $rolePermissions->pluck('id')->toArray() : null;
        } else {
            $rolePermissions = [];
        }

        return [
            'allPermissionsLists' => !empty($allPermissionsLists) ? $allPermissionsLists : null,
            'allGroups'           => !empty($allPermissionsLists) ? array_values(array_unique($allPermissionsLists->pluck('group')->toArray())) : null,
            'rolePermissions'     => $rolePermissions,
        ];
    }
}
