<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\PostJobController;
use App\Http\Controllers\StripeExampleController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAuthMiddleware;
use App\Http\Middleware\EmployerMiddleware;
use App\Http\Middleware\isEmployer;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [JobListingController::class, 'index'])->name('listing.index');

Route::get('/register/seeker', [UserController::class, 'createSeeker'])->name('create.seeker')->middleware(CheckAuthMiddleware::class);
Route::post('/register/seeker', [UserController::class, 'storeSeeker'])->name('store.seeker');
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware(CheckAuthMiddleware::class);
Route::post('/login', [UserController::class, 'postLogin'])->name('login.post');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

// ===============================================================================================
// ROUTE FOR USER PROFILE
Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile')->middleware('auth');
Route::post('/user/profile', [UserController::class, 'update'])->name('user.update.profile')->middleware('auth');
Route::get('/user/profile/seeker', [UserController::class, 'seekerProfile'])->name('seeker.profile')->middleware('auth');
Route::post('/user/password', [UserController::class, 'changePassword'])->name('user.password');
Route::post('/upload/resume', [UserController::class, 'uploadResume'])->name('upload.resume');
// ===================================================================================================


// ========================================================================================================
// REGISTRATION AND VERIFICATION ROUTE
Route::get('/register/employer', [UserController::class, 'createEmployer'])->name('create.employer')->middleware(CheckAuthMiddleware::class);
Route::post('/register/employer', [UserController::class, 'storeEmployer'])->name('store.employer');
Route::get('/verify', [DashboardController::class, 'verify'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/login');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::middleware(['auth', 'verified', EmployerMiddleware::class])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
Route::get('/resend/verification/email', [DashboardController::class, 'resend'])->name('resend.email');
// ==========================================================================================================


// ========================================================================================================
// ROUTES FOR EMPLOYER
Route::middleware(['auth', isEmployer::class])->group(function(){
    Route::get('/subscribe', [SubscriptionController::class, 'subscribe']);

    // ===================================================================================
    // stripe example routes starts here
    Route::get('/form', [StripeExampleController::class, 'form'])->name('form');
    Route::post('/charge', [StripeExampleController::class, 'charge'])->name('charge');
    // stripe example routes ends here
    // ===================================================================================

    Route::get('/pay/weekly', [SubscriptionController::class, 'initiatePayment'])->name('pay.weekly');
    Route::get('/pay/monthly', [SubscriptionController::class, 'initiatePayment'])->name('pay.monthly');
    Route::get('/pay/yearly', [SubscriptionController::class, 'initiatePayment'])->name('pay.yearly');
    Route::get('/payment/success', [SubscriptionController::class, 'paymentSuccessful'])->name('payment.success');
    Route::get('/payment/fail', [SubscriptionController::class, 'paymentFailed'])->name('payment.fail');

    Route::get('/job/create', [PostJobController::class, 'create'])->name('job.create');
    Route::post('/job/store', [PostJobController::class, 'store'])->name('job.store');
    Route::get('/job/{listing}/edit', [PostJobController::class, 'edit'])->name('job.edit');
    Route::put('/job/{id}/edit', [PostJobController::class, 'update'])->name('job.update');
    Route::get('jobs', [PostJobController::class, 'index'])->name('jobs.index');
    Route::get('jobs/{listing}/delete', [PostJobController::class, 'destroy'])->name('jobs.delete');
    Route::get('applicants', [ApplicantController::class, 'index'])->name('applicants.index');
    Route::get('applicants/{slug}', [ApplicantController::class, 'show'])->name('applicants.show');
    Route::post('shortlist/{listingId}/{userId}', [ApplicantController::class, 'shortlist'])->name('applicants.shortlist');

});

// =============================================================================================================
// ROUTE FOR POSTED JOB OPENINGS
Route::get('/jobs/{listing:slug}', [JobListingController::class, 'show'])->name('job.show');
Route::post('resume/upload', [FileUploadController::class, 'store']);
Route::post('/application/{listingId}/submit', [ApplicantController::class, 'apply'])->name('application.submit');
Route::get('/company/{id}', [JobListingController::class, 'company'])->name('company');


// ===============================================================================================================
// EXTRA ROUTES ADDED
Route::get('user/job/applied', [UserController::class, 'jobApplied'])->name('job.applied');
