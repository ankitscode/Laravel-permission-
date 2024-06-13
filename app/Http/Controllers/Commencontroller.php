<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Media;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Permission;

class CommenController extends Controller
{
    public static function allPermissions()
{
    return Permission::whereIn('guard_name', ['web', 'doctor'])->get(['id', 'name', 'guard_name']);;
}

    public static function getRolePermission($role_id)
    {
        if (!empty($role_id)) {
            $role = Role::find($role_id);
            return $role->permissions();
        } else {
            return null;
        }
    }

    public static function showRolePermission($role_id, $group_type, $type = '')
    {
        #get all permission
        $allPermissionsLists  = self::allPermissions($group_type, $type);

        #get role permission ids
        if ($role_id) {
            $rolePermissions    = self::getRolePermission($role_id);
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
