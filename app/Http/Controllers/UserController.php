<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;
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
                $link = '<a href="' . route('edituser', $user->id) . '" class="btn btn-primary btn-sm">Edit</a>  ' .
                    '<a href="javascript:void(0)" onclick="deleteUser(' . $user->id . ')"class="btn btn-danger btn-sm">Delete</a>';
                return $link;
            })
            ->editColumn('image', function ($user) {
                return asset('storage/images/' . $user->image);
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
            'name' => 'required|string',
            'email' => 'required|email',
            'petname' => 'required|max:10',
            'breed' => 'required',
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
        $user->breed = $request->input('breed');
        $user->petname = $request->input('petname');
        $user->image = $imageName;
        $user->save();
        return response()->json(['message' => 'User created successfully'], 200);
    }
    /**
     * 
     * Display the specified resource.
     */

    public function show($id)
    {
        $user = User::find($id);
        // dd($Users,$id);
        return view('profile.viewuser', compact('user'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            return abort(404);
        }
        return view('admin.user.edituser', compact('user'));
    }
    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'petname' => 'required',
            'breed' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:6048',
        ]);

        $user = User::findOrFail($request->id);
        $user->name = $request->name;
        // $user->email = Crypt::encryptString($request->email);
        // $user->email = Crypt::decryptString($request->email);
        $user->email = $request->email;
        $user->petname = $request->petname;
        $user->breed = $request->breed;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName);
            $user->image =$imageName;
        }
        $user->save();
        return redirect()->route('userindex');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::find($id)->delete();;
    }
}
