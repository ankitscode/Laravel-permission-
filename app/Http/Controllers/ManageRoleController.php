<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreRoleRequest;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\UpdateRoleRequest;

class ManageRoleController extends Controller
{
    public function index()
    {
        // if (!auth_permission_check('View All Roles')) return redirect()->back();
        // try {
            return view('admin.role.index');
        // } catch (\Exception $e) {
        //     Log::error('#### ManageRoleController -> index() #### ' . $e->getMessage());
        //     Session::flash('alert-error', __('message.something_went_wrong'));
        //     return redirect()->back();
        // }
    }

    public function create()
    {
        if (!auth_permission_check('Create Role')) return redirect()->back();
        try {
            $permission = CommenController::showRolePermission(null, 1);
            return view('admin.role.create', compact('permission'));
        } catch (\Exception $e) {
            Log::error('#### ManageRoleController -> create() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        if (!auth_permission_check('Create Role')) return redirect()->back();
        DB::beginTransaction();
        try {
            $role = Role::create([
                'name'       => $request->role_name,
                'guard_name' => 'web',
            ]);
            $role->syncPermissions($request->permission);
            DB::commit();
            Session::flash('alert-success', __('message.records_created_successfully'));
            return redirect()->route('admin.showRole', $role->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('#### ManageRoleController -> store() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withInput();
        }
    }

    public function show($id)
    {
        if (!auth_permission_check('View Role Details')) return redirect()->back();
        if (isset($id) && !Role::where('id', $id)->exists()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
        try {
            $role = Role::where('id', $id)->first();
            $permission = CommenController::showRolePermission($role->id,$id);
            return view('admin.role.show', compact('role', 'permission'));
        } catch (\Exception $e) {
            Log::error('#### ManageRoleController -> show() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        if (!auth_permission_check('Edit Role')) return redirect()->back();
        if (isset($id) && !Role::where('id', $id)->exists()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
        try {
            $role = Role::where('id', $id)->first();
            $permission = CommenController::showRolePermission($role->id,$id);
            return view('admin.role.edit', compact('role', 'permission'));
        } catch (\Exception $e) {
            Log::error('#### ManageRoleController -> edit() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        if (!auth_permission_check('Edit Role')) return redirect()->back();
        // dd($request->all(),$id);
        if (isset($id) && !Role::where('id', $id)->exists()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $role = Role::where('id', $id)->first();
            $role->name       = $request->name;
            $role->guard_name = 'web';
            $role->save();
            if ($role->id !== 1){
                $role->syncPermissions($request->permission);
            }
            DB::commit();
            Session::flash('alert-success', __('message.records_updated_successfully'));
            return redirect()->route('admin.showRole', $role->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('#### ManageRoleController -> update() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withInput();
        }
    }

    #dataTable
    public function dataTableRolesListTable(Request $request)
    {
        if (!auth_permission_check('View All Roles')) DataTables::of([])->make(true);
        try {
            #main query
            $roles = Role::query();
            return Datatables::of($roles)->make(true);
        } catch (\Exception $e) {
            Log::error('#### ManageRoleController -> dataTableRolesListTable() ####### ' . $e->getMessage());
            Datatables::of([])->make(true);
        }
    }

    // public function destroy($id){

    //     Role::deleted();
    // }
}
