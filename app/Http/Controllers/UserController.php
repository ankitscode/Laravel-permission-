<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserNotification;
use App\Events\NewUsers;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\pet;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use App\Mail\CreatedUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

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

    public function datatab()
    {
        return Datatables::of(User::query())
            ->addColumn('Action', function ($user) {
                $link = '<a href="javascript:void(0)" onclick="editUser(' . $user->id . ') " class="btn btn-primary btn-sm">Edit</a> ' .
                    // '<a href="' .  '" class="btn btn-secondary btn-sm">View</a> ' .
                    '<a href="javascript:void(0)" onclick="deleteUser(' . $user->id . ')"class="btn btn-danger btn-sm">Delete</a>';
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
    //     return view('Admin.User.createuser');
    // }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string',
                'email' => 'required|email',
                'petname' => 'required|max:10',
                'breed' => 'required',
            ]
        );
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->breed = $request->input('breed');
            $user->petname = $request->input('petname');
            // $user->password = Hash::make($request->password);
            // event(new NewUsers($User));//mail notification
            // $User->notify(new NewUserNotification($User));
            // dd('done');
            $user->save();            
            // $role = Role::where('name','admin')->first();
            // $User->assignRole($role);

            // $permission= Permission::where('name','create_user')->first();
            // $User->givePermissionTo($permission);
            // $role->hasPermissionTo('create_user'); 
            // dd($permission,$role,$User);
            //  Mail::to($User->email)->send(new CreatedUser($User));
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
        return response()->json($user);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required',
                'petname' => 'required',
                'breed'=>'required',
            ]
        );
              $id = $request['id'];
              $user = User::find($id); {
              $user->name = $request->input('name');
              $user->email = $request->input('email');
              $user->petname = $request->input('petname');
              $user->breed = $request->input('breed');
            $user->save();
            return redirect(route('userindex'));
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::find($id)->delete();;
    }
}
