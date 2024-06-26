<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class Doctorcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login()
    {
        return view('auth.doctorlogin');
    }

    public function signUp()
    {
        return view('auth.doctorregister');
    }
    public function index()
    {
        return view('admin.doctor.doctorindex');
    }

    public function datatable()
    {
        return Datatables::of(Doctor::query())
            ->addColumn('Action', function ($doctor) {
                $editLink = '';
                $deleteLink = '';

                // Check if user can edit treatment
                if (Auth::guard('doctor')->user()->can('Edit', 'doctor')) {
                    $editLink = '<a href="javascript:void(0)" onclick="editDoctor(' . $doctor->id . ')" class="btn btn-info btn-sm">Edit</a>';
                }

                // Check if user can delete doctor
                if (Auth::guard('doctor')->user()->can('Edit', 'doctor')) {
                    $deleteLink = '<a href="javascript:void(0)" onclick="deleteDoctor(' . $doctor->id . ')" class="btn btn-danger btn-sm">Delete</a>';
                }

                // Combine edit and delete links
                $links = $editLink . ' ' . $deleteLink;

                return $links;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth_permission_check('create','doctor')) return redirect()->back();
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'degree' => 'required|string',
                'patient_name' => 'required|string',
                'treatment' => 'required|string',
            ]);
            // Create a new doctor
            $doctor = new Doctor;
            $doctor->name = $request->input('name');
            $doctor->degree = $request->input('degree');
            $doctor->email = $request->input('email');
            $doctor->treatment = $request->input('treatment');
            $doctor->patient_name = $request->input('patient_name');
            $doctor->save();
            return response()->json(['message' => 'Doctor created successfully'], 200);
            //code...
        } catch (\Exception $e) {
            Log::error('#### DoctorRoleController -> store() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $doctor = Doctor::find($id);
        return view('profile.viewdoctor', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!auth_permission_check('Edit', 'doctor')) return redirect()->back();
        try {
            //code...
            $Doctor = Doctor::find($id);
            return response()->json($Doctor);
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
        if (!auth_permission_check('Edit', 'doctor')) return redirect()->back();

        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'degree' => 'required|string',
                'patient_name' => 'required|string',
                'treatment' => 'required|string',
            ]);

            $doctor = Doctor::find($id);
            $doctor->name = $request->input('name');
            $doctor->degree = $request->input('degree');
            $doctor->email = $request->input('email');
            $doctor->treatment = $request->input('treatment');
            $doctor->patient_name = $request->input('patient_name');
            $doctor->save();
            return response()->json(['success' => 'doctor updated successfully']);
        } catch (\Exception $e) {
            Log::error('#### DoctorController -> update() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!auth_permission_check('Edit','doctor')) return redirect()->back();
        try {
            //code...
            $doctor = Doctor::find($id);
            $doctor->delete();
            return redirect()->route('doctorindex');
        }catch (\Exception $e) {
            Log::error('#### DoctorController -> destroy() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function change_Password()
    {

        return view('admin.user.changepassword');
    }

    // public function 
}
