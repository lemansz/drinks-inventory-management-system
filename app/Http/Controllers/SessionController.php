<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    // Show the login form
    public function create()
    {
        return view('auth.login');
    }


    // Handle the login attempt
    public function store (Request $request) 
    {
        // Validate the incoming request
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']

        ]);

        // Attempt to log the user in

        if (Auth::attempt($credentials))
        {
            $request->session()->regenerate();

            return redirect('/dashboard')->with('success', 'You are now logged in!');
        }
        
        return back()->withErrors([
            'password' => 'Invalid email or password. Please try again.'
        ])->withInput($request->except('password'));
    }

    // Log the user out
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

    
        return redirect('/login');
    }
}

