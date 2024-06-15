<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobEditFormRequest;
use App\Http\Requests\JobPostFormRequest;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class PostJobController extends Controller
{

    public function index(){
        $job = Listing::where('user_id', auth()->id())->get();

        return view('job.index', [
            'jobs' => $job
        ]);
    }

    public function create(){
        return view('job.create');
    }

    public function store(JobPostFormRequest $request){
        $imagePath = $request->file('feature_image')->store('images', 'public');

        Listing::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title).'.'.Str::uuid(),
            'description' => $request->description,
            'roles' => $request->roles,
            'address' => $request->address,
            'salary' => $request->salary,
            'application_close_date' => $request->application_close_date,
            'job_type' => $request->job_type,
            'feature_image' => $imagePath
        ]);

        Alert::success("Success", 'Job listed successfully');
        return back();
    }

    // this shows the edit form
    public function edit(Listing $listing){
        return view('job.edit', [
            'listing' => $listing
        ]);
    }

    // update method
    public function update($id, JobEditFormRequest $request){
        if($request->hasFile('feature_image')){
            $imagePath = $request->file('feature_image')->store('images', 'public');
            Listing::findOrFail($id)->update(['feature_image' => $imagePath ]);
        }

        Listing::findOrFail($id)->update([
            $request->except('feature_image')
        ]);

        Alert::success("Success", 'Job updated successfully');
        return back();
    }

    public function destroy(Listing $listing){
        $listing->delete();

        Alert::success("Success", 'Job deleted');
        return back();
    }
}
