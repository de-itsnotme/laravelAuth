<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use \Hash;



class LoginUserController extends Controller
{

    public function login() {
        return view('layouts/login');
    }

    public function logout() {
        Auth::logout();
        return redirect('login');
    }

    public function loginPost(Request $request) {


        // VALIDATE ENTRIES
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

    	
    	// CHECK TO SEE IF THERE IS AN ENTRY WITH THIS EMAIL ID
    	$user = DB::table('users')->where('email', $request->email)->first();
    	
    	if($user) {

            // IF EXISTS CHECK IF PASSWORD MATCHES
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                // AUTHENTICATION WAS SUCCESSFUL
                return redirect()->intended('dashboard');
            } else {
                // AUTHENTICATION FAILED
                return redirect()->back()->withInput()->withErrors(['layouts/login' => 'Your login credentials are incorrect. Please retry!']);
            }

    	} else {
    		// REDIRECT TO REGISTER PAGE -> BECAUCE THIS EMAIL ADDRESS WAS NOT FOUND IN OUR DATABASE
            return redirect('register')->withInput()->withErrors(['layouts/register' => "You do not already have an account with us. But that's just few clicks away! Sign-up and get started!! =D"]);
    	}
    }


}