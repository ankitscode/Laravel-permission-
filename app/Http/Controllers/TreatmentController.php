<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Doctor;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Session\Session;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth_permission_check('View', 'doctor')) return redirect()->back();
        try {

            $Treatments = Treatment::get();
            $Pets = Pet::get();
            $Doctors = Doctor::get();
            return view('admin.doctor.doctortreatment.treatmentindex', compact('Treatments', 'Pets', 'Doctors'));
            //code...
        } catch (\Exception $e) {

             Log::error('#### TreatmentController -> index() #### ' .$e->getMessage());
             Session('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    /**
     * for datatabel  
     */

    public function treatmentTablelist(Request $request)
    {
        return Datatables::of(Treatment::query()
            ->select('treatments.id', 'pets.petname', 'doctors.name as doctor_name', 'treatments.treatment', 'treatments.note', 'treatments.created_at', 'treatments.updated_at')
            ->join('pets', 'treatments.pet_id', '=', 'pets.id')
            ->join('doctors', 'treatments.doc_id', '=', 'doctors.id'))
            ->addColumn('Action', function ($treatment) {
                $link =  '<a href="javascript:void(0)" onclick="editTreatment(' . $treatment->id . ')" class="btn btn-info btn-sm">Edit</a>' .
                    '<a href="" onclick="deleteTreatment(' . $treatment->id . ') "class="btn btn-danger btn-sm">Delete</a>';
                return $link;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $Pets = Pet::get();
    //     $Doctors = Doctor::get();
    //     return view('Treatment.createtreatment', compact('Pets', 'Doctors'));
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth_permission_check('create','doctor')) return redirect()->back();
        try {
            $request->validate([
                'doc_id' => 'required',
                'pet_id' => 'required',
                'treatment' => 'required',
                'note' => 'required',
            ]);
            $Treatment = new Treatment();
            $Treatment->doc_id = $request->input('doc_id');
            $Treatment->pet_id = $request->input('pet_id');
            $Treatment->treatment = $request->input('treatment');
            $Treatment->note = $request->input('note');
            $Treatment->save();
            return response([
                'message' => 'user add successfull',
            ]);
        } catch (\Exception $e) {
            Log::error('#### TreatmentController -> store() #### ' . $e->getMessage());
            Session('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Treatment = Treatment::with('pets', 'doctors')->find($id);
        return view('Treatment.treatmentview', compact('Treatment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth_permission_check('Edit','doctor')) return redirect()->back();
        try {
            //code...
            $Treatment = Treatment::with('pets', 'doctors')->find($id);
            return response()->json($Treatment);
        } catch (\Exception $e) {
            Log::error('#### TreatmentController -> edit() #### ' . $e->getMessage());
            Session('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
        // return view('Treatment.updatetreatment',compact('Treatment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth_permission_check('Edit','doctor')) return redirect()->back();
        try {
            //code
            $request->validate([
                'doc_id' => 'required',
                'pet_id' => 'required',
                'treatment' => 'required',
                'note' => 'required',
    
            ]);
            $Treatment = Treatment::find($id);
            $Treatment->doc_id = $request->input('doc_id');
            $Treatment->pet_id = $request->input('pet_id');
            $Treatment->treatment = $request->input('treatment');
            $Treatment->note = $request->input('note');
            $Treatment->save();
            return response([
                'message' => 'doctor updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('#### TreatmentController -> update() #### ' . $e->getMessage());
            Session('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth_permission_check('Edit','doctor')) return redirect()->back();
        try {
            //code...
            $Treatment = Treatment::with('pets', 'doctors')->find($id)->delete();
            return response('deleted successfully');
        } catch (\Exception $e) {
            Log::error('#### TreatmentController -> destroy() #### ' . $e->getMessage());
            Session('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
            
        }
    }
}
