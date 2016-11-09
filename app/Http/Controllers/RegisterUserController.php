<?php

namespace App\Http\Controllers;

use Mail;
use App\User;
use App\Http\Controllers\Controller;
use App\Mail\MyMail;
use Illuminate\Mail\Mailer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use \Hash;



class RegisterUserController extends Controller
{

    public function register() {
        // SIMPLY SHOW THE REGISTER VIEW
        return view('layouts/register');
    }

    public function registerPost(Request $request, Mailer $mailer) {
        // CHECK TO SEE IF THERE IS AN ENTRY WITH THIS EMAIL ID
        $user = DB::table('users')->where('email', $request->email)->first();

        if($user) {
            // DUPLICATE ENTRY FOUND
            return redirect()->back()->withInput()->withErrors(['layouts/register' => "Oops, the email address is already taken. you cound: #1. try logging in or #2. recover the password."]);
        } else {
            // IS A NEW EMAIL ADDRESS -> CONTINUE

            // VALIDATE ENTRIES
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required|min:5',
            ]);

            // BUILD HASHED PASSWORD FOR SAVING
            $hashedPassword = Hash::make($request->password);
            $verifyToken = md5(time());

            // BUILD THE NEW USER OBJECT FOR SAVING
            $user = new User();

            // PUSH ALL DATA
            $user->name = $request->name;
            $user->email = $request->email;
            $user->vfytoken = $verifyToken;
            $user->password = $hashedPassword;

            // FINALLY PERSIST TO THE DATABASE
            $user->save();

            // STEP1: SEND OUT VALIDATION EMAILS

                // BUILD THE EMAIL VERIFICATION URL
                    // URL FORMAT IS: http://[domain-name]/<email-address>/<hash-token>
                    // CATCHER ROUTE FOR THIS URL IS: Route::get('verify/{email}/{hash}', 'RegisterUserController@verifyEmail');
                    $verifyUrl = url('/').'/verify/'.$request->email.'/'.$verifyToken;

                    // <EMAILER CODE HERE>
                    $data = array(
                        'name' => $request->name,
                        'link' => $verifyUrl,
                    );

                    $mailer
                        ->to($request->email)
                        ->send(new MyMail($data));


            // STEP2: AUTO-AUTHENTICATE THE NEW USER
                if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    // SUCCESS, REDIRECT USET TO DASHBOARD
                    return redirect()->intended('dashboard');
                } else {
                    // SOMETHING WENT WRONG, FALLBACK -> ASK THE USER TO LOGIN LIKE NORMALLY THEY WOULD IN FUTURE
                    return redirect('login')->withErrors(['layouts/login' => "Please login to get started"]);
                }

        }

    }

    public function verifyEmail($email,$hash) {

        // FIND AND UPDATE THIS USER, 'IFF' NOT BEFORE
        $user = DB::table('users')->where([
            ['email', '=', $email],
            ['vfytoken', '=', $hash],
            ['verified', '=', false],
        ])->update(['verified' => true]);
        return redirect('login')->withErrors(['layouts/login' => "Yahoo! Your account is now verified. Login to continue."]);
    }


}
