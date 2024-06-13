<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Session\Session;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $count = User::count();
        // dd('hello');
        $countDoctors = Doctor::count();

        $latestUsers = User::latest()->take(5)->get();

        return view('dashboard.dashboard', compact('count', 'countDoctors','latestUsers'));
    }


    public function refresh()
    {
        $count = User::count();
        
        $countDoctors = Doctor::count();

        return response()->json([
            'count' => $count,
            'countDoctors' => $countDoctors,
        ]);
    }
 

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return  view('dashboard.dashboard');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function changeLanguage($lang)
    {
        App::setLocale($lang);
        Session::put("locale",$lang);
        return redirect()->back();
    
    }
}
