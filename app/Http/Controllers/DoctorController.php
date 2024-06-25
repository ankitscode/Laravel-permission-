<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
                $link = '<a href="javascript:void(0)" onclick="editDoctor(' . $doctor->id . ')" class="btn btn-primary btn-sm">Edit</a> '  .
                    '<a href="javascript:void(0)" onclick="deleteDoctor(' . $doctor->id . ')" class="btn btn-danger btn-sm" id="deleteButton">Delete</a>';
                return $link;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        $Doctor = Doctor::find($id);
        return response()->json($Doctor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $doctor = Doctor::find($id);
        $doctor->delete();
        return redirect()->route('doctorindex');
    }

    public function change_Password(){

        return view('admin.user.changepassword');

    }

    // public function 
}
