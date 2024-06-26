<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class DoctorRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth_permission_check('View','doctor')) return redirect()->back();
        try {
            $doctor=Doctor::get();
            return view('admin.doctor.doctor_role.doctorroleindex',compact('doctor'));
        } catch (\Exception $e) {
            Log::error('#### ManageRoleController -> index() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth_permission_check('create','doctor')) return redirect()->back();
        try {
            $permission = DoctorCommenController::showDoctorRolePermission(null, 1);
            return view('admin.doctor.doctor_role.createdoctorrole', compact('permission'));
        } catch (\Exception $e) {
            Log::error('#### DoctorRoleController -> create() #### ' . $e->getMessage());
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
        if (!auth_permission_check('create','doctor')) return redirect()->back();
        DB::beginTransaction();
        try {
            $role = Role::create([
                'name'       => $request->role_name,
                'guard_name' => 'doctor',
            ]);
            $role->syncPermissions($request->permission);
            DB::commit();
            Session::flash('alert-success', __('message.records_created_successfully'));
            return redirect()->route('doctor.showRole', $role->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('#### DoctorRoleController -> store() #### ' . $e->getMessage());
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
            $permission = DoctorCommenController::showDoctorRolePermission($role->id,$id);
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
            $permission = DoctorCommenController::showDoctorRolePermission($role->id,$id);
            return view('admin.doctor.doctor_role.editdoctorrole', compact('role', 'permission'));
        } catch (\Exception $e) {
            Log::error('#### DoctorRoleController -> edit() #### ' . $e->getMessage());
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
            $role->guard_name = 'doctor';
            $role->save();
            if ($role->id !== 1){
                $role->syncPermissions($request->permission);
            }
            // dd($role);
            DB::commit();
            Session::flash('alert-success', __('message.records_updated_successfully'));
            return redirect()->route('doctor.showRole', $role->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('#### DoctorRoleController -> update() #### ' . $e->getMessage());
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
    public function dataTableRoles()
{
    return DataTables::of(Role::where('guard_name', 'doctor')->get())
        ->addColumn('Role', function ($role) {
            return $role->name; // Assuming 'name' is the attribute representing the role name
        })
        ->addColumn('Action', function ($role) {
            $editLink = '';
            $viewLink = '';

            // Corrected usage of \Auth
            if (Auth::guard('doctor')->user()->can('Edit', 'doctor')) {
                $editLink = '<a href="'. route('doctor.editRole', ['id' => $role->id]). '" class="ri-edit-2-fill fs-16"></a>';
            }

            // Corrected usage of \Auth
            if (Auth::guard('doctor')->user()->can('View', 'doctor')) {
                $viewLink = '<a href="'. route('doctor.showRole', ['id' => $role->id]). '" class="ri-eye-fill fs-16"></a>';
            }

            // Combine edit and view links
            $links = $editLink. ' '. $viewLink;

            return $links;
        })
        ->rawColumns(['Action']) // Ensure raw HTML is rendered for the Action column
        ->make(true); // Return JSON response
}

}
