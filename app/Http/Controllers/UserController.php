<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeekerRegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    // public function index(){
    //     return view('users.home');
    // }


    // route for registering job seekers
    public function createSeeker(){
        return view('users.seeker-register');
    }


    // stores or registers a seeker
    public function storeSeeker(SeekerRegistrationRequest $request){

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => 'seeker'
        ]);

        Auth::login($user);

        $user->sendEmailVerificationNotification();

        Alert::success("Welcome", 'Your account was created');

        return redirect()->route('verification.notice');
    }


    // login form
    public function login(){
        return view('users.login');
    }


    // login functionality
    public function postLogin(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only(['email', 'password']);

        if(Auth::attempt($credentials)){
            if(auth()->user()->user_type === 'employer'){
                Alert::success("Welcome", 'You Are Now Logged In');
                return redirect()->intended('dashboard');
            }

            // REDIRECTS USERS THAT ARE NOT EMPLOYER TO:
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }


    // logout function
    public function logout(){
        auth()->logout();

        Alert::success("Thank you", 'Log Out Successful');

        return redirect()->route('login');
    }


    // employer register form
    public function createEmployer(){
        return view('users.employer-register');
    }


    // creates employer
    public function storeEmployer(SeekerRegistrationRequest $request){

        // this will create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => 'employer',
            'user_trial' => now()->addWeek(),
        ]);

        // this will login the user
        Auth::login($user);

        // this will send verification mail
        $user->sendEmailVerificationNotification();

        // this will send a sweet alert message
        Alert::success("Welcome", 'Account created successfully');

        // return redirect()->route('login');

        // this will redirect user to a page that tells him his account is not yet verified.
        return redirect()->route('verification.notice');
    }


    // RETURNS PROFILE PAGE VIEW
    public function profile(){
        return view('profile.index');
    }


    // UPDATE PROFILE FUNCTION
    public function update(Request $request){
        if($request->hasFile('profile_pic')){
            $imagePath = $request->file('profile_pic')->store('profile', 'public');
            User::findOrFail(auth()->id())->update([
                'profile_pic' => $imagePath
            ]);
        }

        User::findOrFail(auth()->id())->update([
            $request->except('profile_pic')
        ]);

        Alert::success("Success", 'Profile updated successfully');
        return back();
    }

    // SEEKER PROFILE
    public function seekerProfile(){
        return view('seeker.profile');
    }

    // CHANGE PASSWORD METHOD
    public function changePassword(Request $request){
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|max:20|confirmed'
        ]);

        $user = auth()->user();

        if(!Hash::check($request->current_password, $user->password)){
            Alert::error('Opsy!!', 'Current password does not match');
            return back();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        Alert::success('Success', 'Password have been updated');
        return back();
    }

    // UPLOAD RESUME METHOD
    public function uploadResume(Request $request){
        $this->validate($request, [
            'resume' => 'required|mimes:pdf,doc,docx',
        ]);

        if($request->hasFile('resume')){
            $resume = $request->file('resume')->store('resume', 'public');

            User::findOrFail(auth()->id())->update([
                'resume' => $resume
            ]);

            Alert::success("Success", 'Resume uploaded successfully');
            return back();
        }
    }

    // JOB APPLIED BY USER
    public function jobApplied(){

    }

}
