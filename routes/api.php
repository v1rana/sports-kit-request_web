<?php

use App\Http\Controllers\hosp\AuthController;
use App\Http\Controllers\hosp\HospController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('user.login');
Route::post('/member-details', [AuthController::class, 'getMemberbasicdetailsfromFIDUID'])->name('ppp.details');
Route::post('/otp-request', [AuthController::class, 'requestOTPforMEMID'])->name('ppp.otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOTPRequestforMEMID'])->name('otp.verify');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/update-details', [HospController::class, 'updateUserDetails']);
    Route::post('/update-role', [HospController::class, 'updateRole']);
    Route::get('/user-details', [HospController::class, 'getUserData']);
    Route::get('/application-list', [HospController::class, 'getUserApplications']);
    Route::get('/pending-application-details', [HospController::class, 'getPendingApplication']);
    Route::get('/completed-application-preview/{application_id}', [HospController::class, 'getCompleteApplication']);


    Route::post('/hosp/education', [HospController::class, 'storeOrUpdateEducation']);
    Route::get('/education-details/{application_id}', [HospController::class, 'getEducationData']);

    Route::post('/hosp/sports-discipline', [HospController::class, 'storeSportDiscipline']);
    Route::get('/sports-discipline-details/{application_id}', [HospController::class, 'getSportDiscipline']);

    Route::post('/hosp/declarations', [HospController::class, 'storeDeclarations']);
    Route::get('/declaration-details/{application_id}', [HospController::class, 'getHospDeclarations']);
    Route::get('/declarations-list', [HospController::class, 'getDeclarations']);
    // to view uploaded file
    
    // ->middleware('auth');
    Route::get('/schedule-list/{event_type}', [HospController::class, 'getSchedule']);
    Route::get('/games', [HospController::class, 'getGames']);
});

Route::get('/certificates/{filename}/{foldername}', [HospController::class, 'download']);


Route::get('/hosp', function () {
    return response()->json([
        ['id' => 1, 'title' => 'Software Engineer'],
        ['id' => 2, 'title' => 'Product Manager'],
    ]);
});