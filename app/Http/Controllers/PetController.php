<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PetController extends Controller
{

    public function datatablepetsindex()
    {
        if (!auth_permission_check('all permission')) {
            return redirect()->back();
        }
    
        try {
            return Datatables::of(Pet::query()->join('users', 'pets.user_id', '=', 'users.id'))
                ->addColumn('user_name', function ($pet) {
                    return $pet->user->name; // Assuming you have a 'user' relationship defined in your Pet model
                })
                ->addColumn('Action', function ($pet) {
                    $editLink = '';
                    $deleteLink = '';
    
                    // Check if user can edit pet
                    if (auth()->user()->can('Edit Role')) {
                        $editLink = '<a href="javascript:void(0)" onclick="editPet(' . $pet->id . ')" class="btn btn-info btn-sm">Edit</a>';
                    }
    
                    // Check if user can delete pet
                    if (auth()->user()->can('Delete User')) {
                        $deleteLink = '<a href="javascript:void(0)" onclick="deletePet(' . $pet->id . ')" class="btn btn-danger btn-sm">Delete</a>';
                    }
    
                    // Combine edit and delete links
                    $links = $editLink . ' ' . $deleteLink;
    
                    return $links;
                })
                ->rawColumns(['Action'])
                ->make(true);
        } catch (\Throwable $th) {
            return response([
                'status' => false,
                'message' => 'Internal server error',
            ]);
        }
    }

    //
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }
    //
    /**
     * Direct to the view page for pets.
     */
    public function create()
    {
        if (!auth_permission_check('all permission')) return redirect()->back();
        try {
            //code...
            $users = User::with('pet')->get();
            return view('admin.user.userpets.userpetsindex', compact('users'));
        } catch (\Exception $e) {
            Log::error('#### PetController -> create() #### ' . $e->getMessage());
            Session('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth_permission_check('all permission')) return redirect()->back();
        try {
            $request->validate(
                [
                    'user_id' => 'required|exists:users,id',
                    'petname' => 'required',
                    'breed' => 'required'
                ]
            );
            $pet = new pet;
            $pet->petname = $request->input('petname');
            $pet->user_id = $request->input('user_id');
            $pet->breed = $request->input('breed');
            $pet->save();
            return response()->json(['success' => 'Pet added successfully!']);
            
        } catch (\Exception $e) {
            Log::error('#### PetController -> store() #### ' . $e->getMessage());
            Session('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }
    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     $Pet = Pet::with('user')->find($id);
    //     // Example of fetching users correctly
    //     return view('Admin.Pet.viewpet', compact('Pet'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!auth_permission_check('Edit Role')) return redirect()->back();
        try {
            //code...
            $Pet = Pet::with('user')->find($id);
            // dd($Pet);
            if (!$Pet) {
                return abort(404);
            }
            return response()->json($Pet);
        } catch (\Exception $e) {
            Log::error('#### PetController -> edit() #### ' . $e->getMessage());
            Session('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!auth_permission_check('Edit Role')) return redirect()->back();
        try {
            //code...
            $request->validate(
                [
                    'user_id' => 'required|exists:users,id',
                    'petname' => 'required', // Fixed typo
                    'breed' => 'required',
                ]
            );
            $Pet = Pet::with('user')->find($id);
            $id = $request['id'];
            $Pet = Pet::find($id); {
                $Pet->user_id = $request->input('user_id');
                $Pet->petname = $request->input('petname');
                $Pet->breed = $request->input('breed');
            }
            $Pet->save();
            return response([$Pet]);
        } catch (\Exception $e) {
            Log::error('#### PetController -> update() #### ' . $e->getMessage());
            Session('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!auth_permission_check('Delete User')) return redirect()->back();
        try {
            //code...
            $pet=Pet::with('user')->find($id)->delete();
            return response([
             'message'=>'pet deleted successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('#### PetController -> destroy() #### ' . $e->getMessage());
            Session('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }
}
