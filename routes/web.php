
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



    Route::get('/login', function () {
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



// Route::get('/register', [RegistrationController::class, 'create'])->name('register.form');
// Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
Route::get('/sarpanch', [GramPanchayatSarpanchController::class, 'index']);
Route::get('/municipal', [MunicipalBodyMemberController::class, 'index']);
//Route::middleware(['auth'])->group(function () {
    // Route::get('/sports-kit', [SportsKitRequisitionController::class, 'create'])->name('sports_kit.form');
    Route::get('/registration-form/{user_id}', [SportsKitRequisitionController::class, 'create'])->name('sports_kit.form');
    Route::post('/sports-kit/store', [SportsKitRequisitionController::class, 'store'])->name('sports_kit.store');
	Route::post('/sports-kit/uploadform', [SportsKitRequisitionController::class, 'uploadform'])->name('sports_kit.uploadform');
	Route::get('/sports-kit/print-temp', [SportsKitRequisitionController::class, 'printTemporary'])->name('sports-kit.print.temp');
	Route::get('/sports-kit/sms', [SportsKitRequisitionController::class, 'sendsms'])->name('sports-kit.send.sms');
    Route::get('/user-logout', [SportsKitRequisitionController::class, 'logout'])->name('sk.logout');
	Route::get('/sports-requests', [SportsKitRequisitionController::class, 'index'])->name('sports.requests');
    Route::get('/gm/dashboard', [SportsKitRequisitionController::class, 'dashboard'])->name('sports_kit.dashboard');
    Route::get('/sport-skit/list', [SportsKitRequisitionController::class, 'list'])->name('sports_kit.list');
	Route::get('/sports-kit/print/{id}', [SportsKitRequisitionController::class, 'print'])->name('sports-kit.print');
    Route::post('/assign-vendor', [SportsKitRequisitionController::class, 'storeVendorAssignment'])->name('assign.vendor.store');
	Route::get('/get-areas/{type}/{district}', [SportsKitRequisitionController::class, 'getAreas']);
	
	
	Route::post('/send-otp', [SportsKitRequisitionController::class, 'sendOTP'])->name('send.otp');
	Route::post('/verify-otp', [SportsKitRequisitionController::class, 'verifyOTP'])->name('verify.otp');
	
	Route::middleware(['session.auth'])->group(function () {
		
		Route::get('/hq/sports-requests', [HQController::class, 'index'])->name('hq.sports.requests');
		Route::get('/hq/kit-verified', [HQController::class, 'kit_verified_list'])->name('hq.sports.verified');
		Route::get('/hq/kit-disbursed', [HQController::class, 'kit_disbursed_list'])->name('hq.sports.disbursed');
		Route::get('/hq/hosp-requests', [HQController::class, 'hosp_requests'])->name('hq.hosp.requests');
		Route::get('/hq/dashboard', [HQController::class, 'dashboard'])->name('hq.sports_kit.dashboard');
		Route::get('/hq/dso', [HQController::class, 'dso_list'])->name('hq.dso');
		Route::get('/hq/dso/edit/{id}', [HQController::class, 'edit_dso'])->name('hq.edit_dso');
		Route::put('/hq/dso/update/{id}', [HQController::class, 'update_dso'])->name('hq.update_dso');
		Route::post('/hq/dso/delete/{id}', [HQController::class, 'delete_dso'])->name('hq.delete_dso');
		Route::get('/hq/show-dso', [HQController::class, 'show_dso_form'])->name('hq.show_dso_form');
		Route::post('/hq/create-dso', [HQController::class, 'dso_create'])->name('hq.create');
		
		Route::get('/hq/vendors', [HQController::class, 'vendor_form'])->name('hq.vendor'); // Vendor form route
		Route::get('/hq/vendor-list', [HQController::class, 'vendor_list'])->name('hq.vendor-list'); // Vendor list route
		Route::get('/vendors/create', [HQController::class, 'create'])->name('vendor.create'); // Add vendor form route
		Route::post('/vendors', [HQController::class, 'store'])->name('vendor.store'); // Store vendor data route
		Route::post('/hq/assign', [HQController::class, 'assignVendor'])->name('hq.assignvendor');
		Route::get('/hq/gradlist', [HQController::class, 'grad_list'])->name('hq.grad.list');
		Route::get('/hq/approved-gradlist', [HQController::class, 'approved_list'])->name('hq.approved_grad.list');
		Route::get('/hq/rejected-gradlist', [HQController::class, 'rejected_list'])->name('hq.rejected_grad.list');
		Route::get('/hq/certificate-issued-gradlist', [HQController::class, 'certificate_issued_list'])->name('hq.certificate_issued_grad.list');
		
		Route::post('/hq/certificates/download-pdf', [HQController::class, 'viewAppliedCertificate'])->name('hq.certificates.downloadPDF');
		Route::post('/hq/certificates/upload-pdf', [HQController::class, 'UploadCertificate'])->name('hq.certificates.uploadPDF');
		Route::post('/hq/enquiry/upload-letter', [HQController::class, 'UploadLetter'])->name('hq.enquiry.UploadLetter');
		Route::post('/hq/enquiry/reply-letter', [HQController::class, 'RepliedLetter'])->name('hq.enquiry.ReplyLetter');
		
		Route::post('/hq/grad-list/approve/{id}', [HQController::class, 'ApproveRequest'])->name('hq.approve');
		Route::post('/hq/grad-list/reject/{id}', [HQController::class, 'RejectRequest'])->name('hq.reject');

		Route::post('/hq/sports-request/approve/{id}', [HQController::class, 'approveOspRequest'])->name('hq.approveReject');
		// Route::post('/hq/sports-request/reject/{id}', [HQController::class, 'rejectOspRequest'])->name('hq.reject');
	
	
	
		
		Route::get('/dso/sports-requests', [DSOController::class, 'index'])->name('dso.sports.requests');
		Route::get('/dso/kit-verified', [DSOController::class, 'kit_verified_list'])->name('dso.sports.verified');
		Route::get('/dso/kit-disbursed', [DSOController::class, 'kit_disbursed_list'])->name('dso.sports.disbursed');
		Route::get('/dso/sports-kit', [DSOController::class, 'create'])->name('dso.sports_kit.form');
		Route::get('/dso/dashboard', [DSOController::class, 'dashboard'])->name('dso.sports_kit.dashboard');
		Route::post('/dso/certificates/download-pdf', [DSOController::class, 'viewAppliedCertificate'])->name('dso.certificates.downloadPDF');
		Route::post('/dso/certificates/upload-pdf', [DSOController::class, 'UploadCertificate'])->name('dso.certificates.uploadPDF');
		Route::post('/dso/enquiry/upload-letter', [DSOController::class, 'UploadLetter'])->name('dso.enquiry.UploadLetter');
		Route::post('/dso/enquiry/reply-letter', [DSOController::class, 'RepliedLetter'])->name('dso.enquiry.ReplyLetter');
		Route::post('/dso/kit-disbursement', [DSOController::class, 'storeKitDisbursement'])->name('dso.kit-disbursement.store');
		
		Route::get('/dso/gradlist', [DSOController::class, 'grad_list'])->name('dso.grad.list');
		Route::get('/dso/approved-gradlist', [DSOController::class, 'approved_list'])->name('dso.approved_grad.list');
		Route::get('/dso/rejected-gradlist', [DSOController::class, 'rejected_list'])->name('dso.rejected_grad.list');
		Route::get('/dso/certificate-issued-gradlist', [DSOController::class, 'certificate_issued_list'])->name('dso.certificate_issued_grad.list');
		Route::post('/dso/sports-request/verify/{id}', [DSOController::class, 'verifyRequest'])->name('dso.verify');
		Route::post('/dso/sports-request/not-verify/{id}', [DSOController::class, 'notVerifyRequest'])->name('dso.not_verify');
		Route::post('/dso/sports-request/approve/{id}', [DSOController::class, 'ApproveRequest'])->name('dso.approve');
		Route::post('/dso/sports-request/reject/{id}', [DSOController::class, 'RejectRequest'])->name('dso.reject');

	});
	/* Route::get('/adc/dashboard', [ADCController::class, 'dashboard'])->name('adc.sports_kit.dashboard');
	Route::get('/adc/sports-requests', [ADCController::class, 'index'])->name('adc.sports.requests');
	Route::post('/adc/sports-request/approve/{id}', [ADCController::class, 'approveRequest'])->name('adc.approve');
	Route::post('/adc/sports-request/reject/{id}', [ADCController::class, 'rejectRequest'])->name('adc.reject'); */



//});


// Protect Dashboard & Authenticated Routes

    Route::get('/dashboard', [SportsGradationCertificateController::class, 'dashboard'])->name('dashboard');
    Route::get('/create', [SportsGradationCertificateController::class, 'create'])->name('sports.create');
    Route::post('/sportsregistration', [SportsGradationCertificateController::class, 'store'])->name('sports.store');
    Route::get('/apply.certificate.form/{user_id}', [SportsGradationCertificateController::class, 'applyCertificate'])->name('apply.certificate.form');
    Route::get('/view.applied.certificate', [SportsGradationCertificateController::class, 'viewAppliedCertificate'])->name('view.applied.certificate');
    // Logout should be POST to prevent CSRF attacks
    Route::get('/get.organising.authority', [SportsGradationCertificateController::class, 'getOrganisingAuthority'])->name('get.organising.authority');

    Route::get('/verify-certificate/{mobile}/{certificate}', [SportsGradationCertificateController::class, 'verifyCertificate'])
    ->name('verify.certificate');
    Route::get('/verification.by.sportsperson/{id}', [SportsGradationCertificateController::class, 'verificationBySportsPerson'])->name('verification.by.sportsperson');

    Route::post('/upload-file', [SportsGradationCertificateController::class, 'uploadFile'])->name('file.upload');
    Route::delete('/file.remove', [SportsGradationCertificateController::class, 'fileRemove'])->name('file.remove');
	Route::post('/verify.status.submit', [SportsGradationCertificateController::class, 'verifyStatusSubmit'])->name('verify.status.submit');
    
    Route::post('/logout', [SportsGradationCertificateController::class, 'logout'])->name('logout');


    Route::get('/', function($any = null) { 
        return view('hosp/app', ['any' => $any]);
    })->where('any', '.*');
    Route::get('/basic-details', function($any = null) { 
        return view('hosp/app', ['any' => $any]);
    })->where('any', '.*');
Route::get('hosp/{any?}', function($any = null) { 
    return view('hosp/app', ['any' => $any]);
})->where('any', '.*');