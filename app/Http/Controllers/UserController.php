<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Encryption\DecryptException;

class Usercontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Users = User::get();
        return view('admin.user.userindex');
    }
    /**
     * 
     * Controller function for user datatabel.
     */
    public function datatab()
    {
        return Datatables::of(User::query())
            ->addColumn('Action', function ($user) {
                $editLink = '';
                $deleteLink = '';
    
                // Check if user can edit
                if (auth()->user()->can('Edit User')) {
                    $editLink = '<a href="' . route('edituser', $user->id) . '" class="btn btn-primary btn-sm">Edit</a>';
                }
    
                // Check if user can delete
                if (auth()->user()->can('Delete User')) {
                    $deleteLink = '<a href="javascript:void(0)" onclick="deleteUser(' . $user->id . ')" class="btn btn-danger btn-sm">Delete</a>';
                }
    
                // Combine edit and delete links
                $links = $editLink . ' ' . $deleteLink;
    
                return $links;
            })
            ->editColumn('image', function ($user) {
                return asset('storage/images/' . $user->image);
            })
            ->rawColumns(['Action']) // Ensure 'Action' column HTML is rendered as raw HTML
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth_permission_check('Add user')) return redirect()->back();
       
        try {
            //code...
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:6048'
            ]);
           
            if ($request->hasFile('image')) {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('public/images', $imageName); // Store the image in storage/app/public/images
                    $imagePath = 'storage/images/' . $imageName;
                }
            }
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->image = $imageName;
            $user->save();
            return response()->json(['message' => 'User created successfully'], 200);
        } catch (\Exception $e) {
            Log::error('#### UserController -> store() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }
    /**
     * 
     * Display the specified resource.
     */

    public function show($id)
    {
        $user = User::find($id);
        return view('profile.viewuser', compact('user'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!auth_permission_check('Edit User')) return redirect()->back();
        try {
            //code...
            $user = User::find($id);
            if (!$user) {
                return abort(404);
            }
            return view('admin.user.edituser', compact('user'));
        } catch (\Exception $e) {
            Log::error('#### UserController -> edit() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }
    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request)
    {    
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:6048',
            ]);

            $user = User::findOrFail($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images', $imageName);
                $user->image = $imageName;
            }
            $user->save();
            return redirect()->route('userindex');
            
        
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!auth_permission_check('Delete User')) return redirect()->back(); 
        try { 
            //code...
            User::find($id)->delete();
        } catch (\Exception $e) {
            Log::error('#### UserController -> destroy() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }
}
