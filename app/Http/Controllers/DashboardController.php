<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{
    //
    public function index(){
        return view('dashboard');
    }

    public function verify(){
        return view('users.verify');
    }

    public function resend(Request $request){
        $user = Auth::user();

        if($user->email_verified_at){
            Alert::success("Success", 'Your email have been verified');
            return redirect()->route('/dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        Alert::success("Success", 'Verification link sent!');

        return back();
    }
}
