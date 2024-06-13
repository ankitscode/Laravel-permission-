<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthDoctorController extends Controller
{
    //
        /**
         * Display the login view.
         */
        // public function create(): View
        // {
        //     return view('auth.login');
        // }
    
        /**
         * Handle an incoming authentication request.
         */
        public function store(Request $request): RedirectResponse
        {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            $credentials = $request->only('email', 'password');
    
            if (Auth::guard('doctor')->attempt($credentials)) {
                return redirect()->route('doctorindex');
            }
    
            return redirect()->back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ]);
        }
    
    
        /**
         * Destroy an authenticated session.
         */
        public function destroy(Request $request): RedirectResponse
        {
            Auth::guard('doctor')->logout();
    
            $request->session()->invalidate();
    
            $request->session()->regenerateToken();
    
            return redirect('/');
        }
    }
    
