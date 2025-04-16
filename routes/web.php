<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\GramPanchayatSarpanchController;
use App\Http\Controllers\MunicipalBodyMemberController;
use App\Http\Controllers\SportsKitRequisitionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SportsGradationCertificateController;
use App\Http\Controllers\HQController;
use App\Http\Controllers\DSOController;
use App\Http\Controllers\ADCController;
use Illuminate\Support\Facades\Route;


// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/send-otp', [AuthController::class, 'sendOTP'])->name('send.otp');
// Route::get('/verify-otp', [AuthController::class, 'showVerifyForm'])->name('verify.otp.form');
// Route::post('/verify-otp', [AuthController::class, 'verifyOTP'])->name('verify.otp');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('login');
    })->name('login');

    Route::get('/create-account', function () {
        return view('create_account');
    });

    Route::post('/login.otp.send', [SportsGradationCertificateController::class, 'loginOtpVerify'])->name('login.otp.send');
    Route::post('/send-otp', [SportsGradationCertificateController::class, 'sendOtp'])->name('otp.send');
    Route::post('/verify-otp', [SportsGradationCertificateController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/resend-otp', [SportsGradationCertificateController::class, 'resendOtp'])->name('otp.resend');
    Route::post('/register', [SportsGradationCertificateController::class, 'create_account'])->name('register.store');
    Route::post('/sports.login', [SportsGradationCertificateController::class, 'login'])->name('sports.login');
});


// Route::get('/register', [RegistrationController::class, 'create'])->name('register.form');
// Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
Route::get('/sarpanch', [GramPanchayatSarpanchController::class, 'index']);
Route::get('/municipal', [MunicipalBodyMemberController::class, 'index']);
//Route::middleware(['auth'])->group(function () {
    Route::get('/sports-kit', [SportsKitRequisitionController::class, 'create'])->name('sports_kit.form');
    Route::post('/sports-kit/store', [SportsKitRequisitionController::class, 'store'])->name('sports_kit.store');
    Route::get('/sports-requests', [SportsKitRequisitionController::class, 'index'])->name('sports.requests');
    Route::get('/gm/dashboard', [SportsKitRequisitionController::class, 'dashboard'])->name('sports_kit.dashboard');
    Route::get('/sport-skit/list', [SportsKitRequisitionController::class, 'list'])->name('sports_kit.list');
    Route::post('/assign-vendor', [SportsKitRequisitionController::class, 'storeVendorAssignment'])->name('assign.vendor.store');
    Route::get('/hq/sports-requests', [HQController::class, 'index'])->name('hq.sports.requests');
    Route::get('/hq/dashboard', [HQController::class, 'dashboard'])->name('hq.sports_kit.dashboard');
Route::post('/hq/assign', [HQController::class, 'assignVendor'])->name('hq.assignvendor');
Route::get('/dso/sports-requests', [DSOController::class, 'index'])->name('dso.sports.requests');
Route::get('/dso/sports-kit', [DSOController::class, 'create'])->name('dso.sports_kit.form');
Route::get('/dso/dashboard', [DSOController::class, 'dashboard'])->name('dso.sports_kit.dashboard');
Route::post('/dso/certificates/download-pdf', [DSOController::class, 'viewAppliedCertificate'])->name('dso.certificates.downloadPDF');
Route::post('/dso/certificates/upload-pdf', [DSOController::class, 'UploadCertificate'])->name('dso.certificates.uploadPDF');

Route::get('/adc/dashboard', [ADCController::class, 'dashboard'])->name('adc.sports_kit.dashboard');
Route::get('/dso/gradlist', [DSOController::class, 'grad_list'])->name('dso.grad.list');
Route::get('/adc/sports-requests', [ADCController::class, 'index'])->name('adc.sports.requests');
Route::post('/adc/sports-request/approve/{id}', [ADCController::class, 'approveRequest'])->name('adc.approve');
Route::post('/adc/sports-request/reject/{id}', [ADCController::class, 'rejectRequest'])->name('adc.reject');

Route::post('/dso/sports-request/verify/{id}', [DSOController::class, 'verifyRequest'])->name('dso.verify');
Route::post('/dso/sports-request/not-verify/{id}', [DSOController::class, 'notVerifyRequest'])->name('dso.not_verify');
Route::post('/dso/sports-request/approve/{id}', [DSOController::class, 'ApproveRequest'])->name('dso.approve');
Route::post('/dso/sports-request/reject/{id}', [DSOController::class, 'RejectRequest'])->name('dso.reject');

//});


// Protect Dashboard & Authenticated Routes
Route::middleware(['auth.session'])->group(function () {
    Route::get('/dashboard', [SportsGradationCertificateController::class, 'dashboard'])->name('dashboard');
    Route::get('/create', [SportsGradationCertificateController::class, 'create'])->name('sports.create');
    Route::post('/sportsregistration', [SportsGradationCertificateController::class, 'store'])->name('sports.store');
    Route::get('/apply.certificate.form', [SportsGradationCertificateController::class, 'applyCertificate'])->name('apply.certificate.form');
    Route::get('/view.applied.certificate', [SportsGradationCertificateController::class, 'viewAppliedCertificate'])->name('view.applied.certificate');
    // Logout should be POST to prevent CSRF attacks
    Route::get('/get.organising.authority', [SportsGradationCertificateController::class, 'getOrganisingAuthority'])->name('get.organising.authority');

    Route::get('/verify-certificate/{mobile}/{certificate}', [SportsGradationCertificateController::class, 'verifyCertificate'])
    ->name('verify.certificate');
    
    Route::post('/logout', [SportsGradationCertificateController::class, 'logout'])->name('logout');
});


Route::get('hosp/{any?}', function($any = null) { 
    return view('hosp/app', ['any' => $any]);
})->where('any', '.*');