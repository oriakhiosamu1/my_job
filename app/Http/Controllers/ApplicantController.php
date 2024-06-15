<?php

namespace App\Http\Controllers;

use App\Mail\ShortlistMail;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class ApplicantController extends Controller
{
    //INDEX METHOD
    public function index(){
        $listings = Listing::latest()->withCount('users')->where('user_id', auth()->id())->get();
        return view('applicants.index', [
            'listings' => $listings
        ]);
    }

    // SHOW METHOD
    public function show($slug){
        $this->authorize('view', $slug);
        $listings = Listing::with('users')->where('slug', $slug)->first();

        return view('applicants.show', [
            'listing' => $listings,
        ]);
    }

    // SHORTLIST METHOD
    public function shortlist($listingId, $userId){
        $listing = Listing::find($listingId);
        $user = User::find($userId);

        if($listing){
            $listing->users()->updateExistingPivot($userId, ['shortlisted'=>true]);
            Mail::to($user->email)->queue(new ShortlistMail($listing ->title, $user->name));
            Alert::success('Success', 'User shortlisted');
        }

        Alert::error('Opsy!!!', 'User not shortlisted');
        return back();
    }

    // APPLY METHOD
    public function apply($listingId){
        $user = auth()->user();
        $user->listings()->syncWithoutDetaching($listingId);

        Alert::success('Success', 'Application successfully submited');
        return back();
    }
}
