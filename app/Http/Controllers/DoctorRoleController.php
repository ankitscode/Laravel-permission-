<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreRoleRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DoctorRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.doctor.doctor_role.doctorroleindex');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth_permission_check('Create','doctor')) return redirect()->back();
        try {
            $permission = CommenController::showRolePermission(null, 1);
            return view('admin.doctor.doctor_role.createdoctorrole', compact('permission'));
        } catch (\Exception $e) {
            Log::error('#### ManageRoleController -> create() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
        // return view('admin.doctor.doctor_role.createdoctorrole');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth_permission_check('Create','doctor')) return redirect()->back();
        DB::beginTransaction();
        try {
            $role = Role::create([
                'name'       => $request->role_name,
                'guard_name' => 'web',
            ]);
            $role->syncPermissions($request->permission);
            DB::commit();
            Session::flash('alert-success', __('message.records_created_successfully'));
            return redirect()->route('doctor.showRole', $role->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('#### ManageRoleController -> store() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!auth_permission_check('View','doctor')) return redirect()->back();
        if (isset($id) && !Role::where('id', $id)->exists()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
        try {
            $role = Role::where('id', $id)->first();
            $permission = CommenController::showRolePermission($role->id,$id);
            return view('admin.doctor.doctor_role.showdoctorrole', compact('role', 'permission'));
        } catch (\Exception $e) {
            Log::error('#### ManageRoleController -> show() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth_permission_check('Edit','doctor')) return redirect()->back();
        if (isset($id) && !Role::where('id', $id)->exists()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
        try {
            $role = Role::where('id', $id)->first();
            $permission = CommenController::showRolePermission($role->id,$id);
            return view('admin.doctor.doctor_role.editdoctorrole', compact('role', 'permission'));
        } catch (\Exception $e) {
            Log::error('#### ManageRoleController -> edit() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth_permission_check('Edit','doctor')) return redirect()->back();
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
            return redirect()->route('doctor.showRole', $role->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('#### ManageRoleController -> update() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * show the roles from roles tabels
     */
    public function dataTableRoles(){
        return Datatables::of(Role::query())
            ->addColumn('Role', function ($role) {
                return $role->name; // Assuming 'name' is the attribute representing the role name
            })
            ->addColumn('Action', function ($role) {
                $editLink = '<a href="' . route('doctor.editRole', ['id' => $role->id]) . '" class="ri-edit-2-fill fs-16"></a>';
                $viewLink = '<a href="' . route('doctor.showRole', ['id' => $role->id]) . '" class="ri-eye-fill fs-16 "></a>';
                // Add other action links as needed
                
                return $editLink . ' ' . $viewLink;
            })
            ->rawColumns(['Action'])
            ->make(true);
        }
}
