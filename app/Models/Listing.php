<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'roles',
        'address',
        'salary',
        'application_close_date',
        'job_type',
        'feature_image'
    ];

    // RELATIONSHIP WITH USERS
    public function users(){
        return $this->belongsToMany(User::class, 'listing_user', 'listing_id', 'user_id')->withPivot('shortlisted')->withTimestamps();
    }

    // RELATIONSHIP WITH USER PROFILE
    public function profile(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
