<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;


if (!function_exists('auth_permission_check')) {
    /**
     * check user auth and permission.
     *
     * @return boolean|integer
     */
    function auth_permission_check($permission,$guard=null)
    {
    //     if (!Auth::user()) {
    //         Session::flash('alert-error', __('message.User not authorized'));
    //         return false;
    //     }

    //     if (!Auth::user()->can($permission)) {
    //         Session::flash('alert-error', __('message.User does not have permission'));
    //         return false;
    //     }
    //     return true;
    // }
    $guard = $guard ?: 'web'; // Default guard is 'web'
        
        if (!Auth::guard($guard)->check()) {
            Session::flash('alert-error', __('message.User not authorized'));
            return false;
        }

        if (!Auth::guard($guard)->user()->can($permission)) {
            Session::flash('alert-error', __('message.User does not have permission'));
            return false;
        }
        return true;
    }
}

if (!function_exists('auth_permission_check_any')) {
    /**
     * check user auth and any permission.
     *
     * @return boolean|integer
     */
    function auth_permission_check_any($permission,$guard=null)
    {
    //     if (!Auth::user()) {
    //         Session::flash('alert-error', __('message.User not authorized'));
    //         return false;
    //     }
    //     if (!Auth::user()->hasAnyPermission($permission)) {
    //         Session::flash('alert-error', __('message.User does not have permission'));
    //         return false;
    //     }
    //     return true;

    $guard = $guard ?: 'web'; // Default guard is 'web'

    if (!Auth::guard($guard)->check()) {
        Session::flash('alert-error', __('message.User not authorized'));
        return false;
    }

    if (!Auth::guard($guard)->user()->hasAnyPermission($permissions)) {
        Session::flash('alert-error', __('message.User does not have permission'));
        return false;
    }
    return true;
}
    // }
}











