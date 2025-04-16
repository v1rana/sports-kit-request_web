<?php

namespace App\Http\Controllers;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use App\Models\sports_gradation_certificate;
use App\Models\SportsGradationUser;
use App\Models\State;
use App\Models\GetDistricts;
use App\Models\GetSportName;
use App\Models\GramPanchayatSarpanch;
use App\Models\DSO;
use App\Models\ADC;
use App\Models\HQ;
use Illuminate\Support\Facades\Storage;

use App\Models\CategoryWiseGradation;
use Carbon\Carbon;

class SportsGradationCertificateController extends Controller
{

    public function create_account(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'dob_month' => 'required',
            'dob_day' => 'required',
            'dob_year' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:6'
        ]);
        $name = $request->first_name . ' ' . $request->last_name;
        $dob = $request->dob_year . '-' . $request->dob_month . '-' . $request->dob_day;     
        
        $data = SportsGradationUser::updateOrCreate(
            ['mobile_no' => $request->mobile], 
            [
                'sports_person_name' => $name,
                'dob' => $dob,
                'gender' => $request->gender,
                'email' => $request->email,                
                'otp' => $request->otp                
            ]
        );

        session([
            'user_name' => $data->sports_person_name,
            'user_email' => $data->email,
            'mobile_no' => $data->mobile_no
        ]);

        return redirect('/')->with('success', 'Account created successfully!');        
       
    }

    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:6'
        ]);      

        if($request->mobile == '9813503099'){
            $otpData = GramPanchayatSarpanch::where('mob', $request->mobile)
            ->where('otp', $request->otp) // ✅ Corrected this line
                        ->first();
            if (!$otpData) {
                return back()->withErrors(['otp' => 'Invalid OTP or Mobile Number.']); // Handle the case properly
            }            

            //session()->regenerate();

            //session([
             //   'user_id' => $otpData->id,
            //     'user_name' => $otpData->sports_person_name,
            //     'user_email' => $otpData->email,
            //     'mobile_no' => $otpData->mobile_no
             // ]);

            $otpData->update(['otp' => null]); // Clear OTP after login  

            if ($otpData && Carbon::now()->lessThan($otpData->otp_expires_at)) {
                
                return redirect()->route('sports_kit.form')->with('success', 'User Loged In successfully!');
            }
        }elseif($request->mobile == '9999999999'){
            $otpData = ADC::where('mob', $request->mobile)
            ->where('otp', $request->otp) // ✅ Corrected this line
                        ->first();
            if (!$otpData) {
                return back()->withErrors(['otp' => 'Invalid OTP or Mobile Number.']); // Handle the case properly
            }            

            //session()->regenerate();

            ///session([
                //'user_id' => $otpData->id,
            //     'user_name' => $otpData->sports_person_name,
            //     'user_email' => $otpData->email,
            //     'mobile_no' => $otpData->mobile_no
             //]);

            $otpData->update(['otp' => null]); // Clear OTP after login  

            if ($otpData && Carbon::now()->lessThan($otpData->expires_at)) {
                
                return redirect()->route('adc.sports_kit.dashboard')->with('success', 'User Loged In successfully!');
            }
        }elseif($request->mobile == '8888888888'){
            $otpData = HQ::where('mob', $request->mobile)
            ->where('otp', $request->otp) // ✅ Corrected this line
                        ->first();
            if (!$otpData) {
                return back()->withErrors(['otp' => 'Invalid OTP or Mobile Number.']); // Handle the case properly
            }            

            //session()->regenerate();

            ///session([
                //'user_id' => $otpData->id,
            //     'user_name' => $otpData->sports_person_name,
            //     'user_email' => $otpData->email,
            //     'mobile_no' => $otpData->mobile_no
             //]);

            //$otpData->update(['otp' => null]); // Clear OTP after login  

            if ($otpData && Carbon::now()->lessThan($otpData->expires_at)) {
                
                return redirect()->route('hq.sports_kit.dashboard')->with('success', 'User Loged In successfully!');
            }
        }elseif($request->mobile == '9728198706'){
            $otpData = DSO::where('mob', $request->mobile)
            ->where('otp', $request->otp) // ✅ Corrected this line
                        ->first();
            if (!$otpData) {
                return back()->withErrors(['otp' => 'Invalid OTP or Mobile Number.']); // Handle the case properly
            }            

            // session()->regenerate();

            // session([
                 //'user_id' => $otpData->id,
                // 'user_name' => $otpData->sports_person_name,
                // 'user_email' => $otpData->email,
                // 'mobile_no' => $otpData->mobile_no
            //  ]);

            $otpData->update(['otp' => null]); // Clear OTP after login  

            if ($otpData && Carbon::now()->lessThan($otpData->expires_at)) {
                
                return redirect()->route('dso.sports.requests')->with('success', 'User Loged In successfully!');
            }
        }else{
            $otpData = SportsGradationUser::where('mobile_no', $request->mobile)
            ->where('otp', $request->otp) // ✅ Corrected this line
            ->first();
            if (!$otpData) {
                return back()->withErrors(['otp' => 'Invalid OTP or Mobile Number.']); // Handle the case properly
            }            

            session()->regenerate();

            session([
                'user_id' => $otpData->id,
                'user_name' => $otpData->sports_person_name,
                'user_email' => $otpData->email,
                'mobile_no' => $otpData->mobile_no
            ]);

            $otpData->update(['otp' => null]); // Clear OTP after login  

            if ($otpData && Carbon::now()->lessThan($otpData->expires_at)) {
                
                return redirect()->route('dashboard')->with('success', 'User Loged In successfully!');
            }
        }
        

        return back()->with(['message' => 'Invalid OTP or expired'], 400);
    }

    public function dashboard()
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login')->withErrors(['message' => 'Please log in first.']);
        }

        $mobile_no = session()->get('mobile_no');
        
        //$otpData = sports_gradation_certificate::where('mobile_no', $mobile_no)->get();

        $otpData = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
        ->where('sports_gradation_certificates.mobile_no', $mobile_no)
        ->whereIn('category_wise_gradations.gradation', ['C', 'D'])
         ->orderBy('sports_gradation_certificates.created_at', 'desc')
         ->select('sports_gradation_certificates.*', 'category_wise_gradations.gradation', 'category_wise_gradations.tournament', 'category_wise_gradations.organising_authority as authority')
         ->get();


        return view('main')->with(['otpData' => $otpData]);
    }

    public function applyCertificate()
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login')->withErrors(['message' => 'Please log in first.']);
        }

        $mobile_no = session()->get('mobile_no');

        $tournaments = CategoryWiseGradation::select('id','tournament','organising_authority')->get(); 
        $state = State::select('id', 'name')->get(); 
        $GetSportName = GetSportName::select('id', 'name')->get();         
        $GetDistricts = GetDistricts::select('id', 'name', 'state_id')->get(); 
        $otpData = SportsGradationUser::where('mobile_no', $mobile_no) // ✅ Corrected this line
        ->first();
        return view('applyCertificate')->with(['otpData' => $otpData,'tournament' =>$tournaments,'state' =>$state,'GetDistricts' =>$GetDistricts,'GetSportName' =>$GetSportName]);
    }

    public function getOrganisingAuthority(Request $request)
    {
        $authorities = CategoryWiseGradation::where('id', $request->tournament_id)
        ->pluck('organising_authority', 'id');

        return response()->json($authorities); 
    }

    public function viewAppliedCertificate()
    {
        // $mobile = '9058736489';
        // $certificate = 'CET-45BC';

        // // Generate a dynamic URL using route() helper
        // $currentURL = route('verify.certificate', ['mobile' => $mobile, 'certificate' => $certificate]);

        //  // Generate the QR code
        // $qrCode = QrCode::size(300)->generate($currentURL);

        // $fileName = 'qrcodes/' . $certificate . '.png';
        // Storage::disk('public')->put($fileName, $qrCode);

        // // Get QR Code URL
        // $qrCodeUrl = asset('storage/' . $fileName);

        // return $qrCode;

        $mobile_no = session()->get('mobile_no');
        $otpData = sports_gradation_certificate::where('mobile_no', $mobile_no) // ✅ Corrected this line
        ->first();
        return view('viewAppliedCertificate')->with(['otpData' => $otpData]);
    }

    public function verifyCertificate($mobile, $certificate)
    {
        return "Verifying certificate: {$certificate} for mobile: {$mobile}";
    }

    public function loginOtpVerify(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10' // Adjust validation as needed
        ]);

        if ($request->mobile == '9813503099') {
            $user = GramPanchayatSarpanch::where('mob', $request->mobile)->first();  
        }elseif ($request->mobile == '9728198706') {
            $user = DSO::where('mob', $request->mobile)->first();  
        }elseif ($request->mobile == '9999999999') {
            $user = ADC::where('mob', $request->mobile)->first();  
        }elseif ($request->mobile == '8888888888') {
            $user = HQ::where('mob', $request->mobile)->first();  
        }
        else{
            $user = SportsGradationUser::where('mobile_no', $request->mobile)->first();  
        }
    
        //$user = SportsGradationUser::where('mobile_no', $request->mobile)->first();  
        

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'This mobile number is not registered.']);
        }

        if($request->mobile == '9813503099'){
            $mobile_no = $request->mobile;
            //$otp = rand(100000, 999999);
            $otp = 111111;
            $data = GramPanchayatSarpanch::updateOrCreate(
                ['mob' => $mobile_no], 
                [
                    'otp' => $otp,
                    'otp_expires_at' => Carbon::now()->addMinutes(5)
                ]
            );
        }elseif($request->mobile == '9728198706'){
            $mobile_no = $request->mobile;
            //$otp = rand(100000, 999999);
            $otp = 111111;
            $data = DSO::updateOrCreate(
                ['mob' => $mobile_no], 
                [
                    'otp' => $otp,
                    'expires_at' => Carbon::now()->addMinutes(5)
                ]
            );
        }elseif($request->mobile == '9999999999'){
            $mobile_no = $request->mobile;
            //$otp = rand(100000, 999999);
            $otp = 111111;
            $data = ADC::updateOrCreate(
                ['mob' => $mobile_no], 
                [
                    'otp' => $otp,
                    'expires_at' => Carbon::now()->addMinutes(5)
                ]
            );
        }elseif($request->mobile == '8888888888'){
            $mobile_no = $request->mobile;
            //$otp = rand(100000, 999999);
            $otp = 111111;
            $data = HQ::updateOrCreate(
                ['mob' => $mobile_no], 
                [
                    'otp' => $otp,
                    'expires_at' => Carbon::now()->addMinutes(5)
                ]
            );
        }else{
            $mobile_no = $request->mobile;
            //$otp = rand(100000, 999999);
            $otp = 111111;
            $data = SportsGradationUser::updateOrCreate(
                ['mobile_no' => $mobile_no], 
                [
                    'otp' => $otp,
                    'expires_at' => Carbon::now()->addMinutes(5)
                ]
            );
        }
        
    
        return response()->json(['success' => true, 'message' => 'OTP sent successfully']);
    }

    public function sendOtp(Request $request)
    { 

        $request->validate([
            'mobile' => 'required|digits:10' // Adjust validation as needed
        ]);    
       
        if($request->mobile == '9813503099'){
            $mobile_no = $request->mobile;
            //$otp = rand(100000, 999999);
            $otp = 111111;
            $data = GramPanchayatSarpanch::updateOrCreate(
                ['mob' => $mobile_no], 
                [
                    'otp' => $otp,
                    'otp_expires_at' => Carbon::now()->addMinutes(5)
                ]
            );
        }elseif($request->mobile == '9728198706'){
            $mobile_no = $request->mobile;
            //$otp = rand(100000, 999999);
            $otp = 111111;
            $data = DSO::updateOrCreate(
                ['mob' => $mobile_no], 
                [
                    'otp' => $otp,
                    'expires_at' => Carbon::now()->addMinutes(5)
                ]
            );
        }elseif($request->mobile == '9999999999'){
            $mobile_no = $request->mobile;
            //$otp = rand(100000, 999999);
            $otp = 111111;
            $data = ADC::updateOrCreate(
                ['mob' => $mobile_no], 
                [
                    'otp' => $otp,
                    'expires_at' => Carbon::now()->addMinutes(5)
                ]
            );
        }elseif($request->mobile == '8888888888'){
            $mobile_no = $request->mobile;
            //$otp = rand(100000, 999999);
            $otp = 111111;
            $data = HQ::updateOrCreate(
                ['mob' => $mobile_no], 
                [
                    'otp' => $otp,
                    'expires_at' => Carbon::now()->addMinutes(5)
                ]
            );
        }else{
            $mobile_no = $request->mobile;
            //$otp = rand(100000, 999999);
            $otp = 111111;
            $data = SportsGradationUser::updateOrCreate(
                ['mobile_no' => $mobile_no], 
                [
                    'otp' => $otp,
                    'expires_at' => Carbon::now()->addMinutes(5)
                ]
            );

        }
       
    
        return response()->json(['success' => true,'message' => 'OTP sent successfully','otp' => $otp]);
        
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:6'
        ]);

        if($request->mobile == '9813503099'){
            $otpData = GramPanchayatSarpanch::where('mob', $request->mobile)
            ->where('otp', $request->otp) // ✅ Corrected this line
            ->first();

            if ($otpData && Carbon::now()->lessThan($otpData->otp_expires_at)) {
                
                return response()->json(['message' => 'OTP verified successfully']);
            }
        }elseif($request->mobile == '9728198706'){
            $otpData = DSO::where('mob', $request->mobile)
            ->where('otp', $request->otp) // ✅ Corrected this line
            ->first();

            if ($otpData && Carbon::now()->lessThan($otpData->expires_at)) {
                
                return response()->json(['message' => 'OTP verified successfully']);
            }
        }elseif($request->mobile == '9999999999'){
            $otpData = ADC::where('mob', $request->mobile)
            ->where('otp', $request->otp) // ✅ Corrected this line
            ->first();

            if ($otpData && Carbon::now()->lessThan($otpData->expires_at)) {
                
                return response()->json(['message' => 'OTP verified successfully']);
            }
        }elseif($request->mobile == '8888888888'){
            $otpData = HQ::where('mob', $request->mobile)
            ->where('otp', $request->otp) // ✅ Corrected this line
            ->first();

            if ($otpData && Carbon::now()->lessThan($otpData->expires_at)) {
                
                return response()->json(['message' => 'OTP verified successfully']);
            }
        }else{
            $otpData = SportsGradationUser::where('mobile_no', $request->mobile)
            ->where('otp', $request->otp) // ✅ Corrected this line
            ->first();

            if ($otpData && Carbon::now()->lessThan($otpData->expires_at)) {
                
                return response()->json(['message' => 'OTP verified successfully']);
            }
        }
       

        return response()->json(['message' => 'Invalid OTP or expired'], 400);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10' // Adjust validation as needed
        ]);    
        // Get the user's mobile number from the request
        $mobile_no = $request->mobile;
        $otp = rand(100000, 999999);
    
        // Save OTP to the database (update if mobile number exists)
        $data = SportsGradationUser::updateOrCreate(
            ['mobile_no' => $mobile_no], 
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5)
            ]
        );
    
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otp // Remove this in production for security reasons
        ]);
    }



    public function create()
    {
        return view('sports_registration_form');
    }

    public function store(Request $request)
    {
        
        $mobile_no = session()->get('mobile_no');
        $request->validate([
            'name' => 'required|string'
        ]);

        $imagePath = null; // Default value to prevent "Undefined variable" error

        if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('uploads', 'public');            
        }
        
        $aadhaar_card = null; // Default value to prevent "Undefined variable" error

        if ($request->hasFile('aadhaar_card')) {
            $aadhaar_card = $request->file('aadhaar_card')->store('uploads', 'public');            
        }

        $domicile_certificate = null; // Default value to prevent "Undefined variable" error

        if ($request->hasFile('domicile_certificate')) {
            $domicile_certificate = $request->file('domicile_certificate')->store('uploads', 'public');            
        }

        $sports_certificate = null; // Default value to prevent "Undefined variable" error

        if ($request->hasFile('sports_certificate')) {
            $sports_certificate = $request->file('sports_certificate')->store('uploads', 'public');            
        }

        $more_than25_photo = null; // Default value to prevent "Undefined variable" error

        if ($request->hasFile('more_than25_photo')) {
            $more_than25_photo = $request->file('more_than25_photo')->store('uploads', 'public');            
        }
        
        $year = date('y'); // Get last two digits of the year (e.g., 2025 -> 25)
        $districtCode = strtoupper(substr($request->district_sportsperson_belongs, 0, 2)); // First 2 letters of district
        $gameCode = strtoupper(substr($request->name_sports_discipline, 0, 2)); // First 2 letters of game

        // Generate a unique 5-character alphanumeric string
        $uniqueCode = strtoupper(substr(md5(uniqid()), 0, 5));

        $certificateNo = "{$year}{$districtCode}{$gameCode}{$uniqueCode}";
                
        sports_gradation_certificate::Create(
            
            [
            'certificate_no'=> $certificateNo,
            'sports_person_name' => $request->name,
            'aadhaar_no' => $request->aadhaar_no,
            'mobile_no' => $mobile_no,
            'district_sportsperson_belongs' => $request->district_sportsperson_belongs,
            'domicile_state' => $request->domicile_state,
            'plays_for_statte_org' => $request->plays_for_statte_org,
            'name_sports_discipline' => $request->name_sports_discipline,
            'tournament_name' => $request->tournament_name,
            'month_year' => $request->month_year,
            'venue_of_tournament' => $request->venue_of_tournament,
            'organising_authority' => $request->organising_authority,
            'tournament_type' => $request->tournament_type,
            'medal_won' => $request->medal_won,
            'participation_level' => $request->participation_level,
            'profile_picture' => $imagePath,
            'aadhaar_card' => $aadhaar_card,
            'domicile_certificate' => $domicile_certificate,
            'sports_certificate' => $sports_certificate,
            'more_than25_photo' => $more_than25_photo,
            'date' => null

        ]);

        return redirect()->back()->with('success', 'Registration Successful!');
    }

    public function logout(Request $request)
    {
        session()->flush();
        session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }
    
}
