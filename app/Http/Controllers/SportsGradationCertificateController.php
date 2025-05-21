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
use App\Models\UserDetails;
use App\Models\CategoryWiseGradation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class SportsGradationCertificateController extends Controller
{
    public function dashboard()
    {  
        $user_id = session('user_id');   

        $otpData = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
        ->where('sports_gradation_certificates.user_id', $user_id)
        
         ->orderBy('sports_gradation_certificates.created_at', 'desc')
         ->select('sports_gradation_certificates.*', 'category_wise_gradations.gradation', 'category_wise_gradations.tournament', 'category_wise_gradations.organising_authority as authority')
         ->get();

        return view('main')->with(['otpData' => $otpData]);
    }

    public function redirectGradation($user_id)
    {
        session([
            'user_id' => $user_id,
        ]);
        return redirect()->route('apply.certificate.form');
    }

    public function applyCertificate($user_id)
    {
        // $encryptedId = Crypt::encryptString($user->id);
        try {
            $userId = Crypt::decryptString($user_id);
            session(['user_id' => $userId]);
        } catch (\Exception $e) {
            abort(403, 'Invalid or tampered ID.');
        }
        // return  $userId;
        if (!session()->has('user_id')) {
            return redirect()->route('login')->withErrors(['message' => 'Please log in first.']);
        }
        // dd(session()->has('user_id'));
        $mobile_no = session()->get('mobile_no')?session()->get('mobile_no'):'';

        $tournaments = CategoryWiseGradation::select('id','tournament','organising_authority')->get(); 
        $state = State::select('id', 'name')->get(); 
        $GetSportName = GetSportName::select('id', 'name')->get();         
        $GetDistricts = GetDistricts::select('id', 'name', 'state_id')->get(); 

        session(['user_id' => $userId]);
        session(['enUserid' => $user_id]);

        $otpData = User::join('user_details', 'users.id', '=', 'user_details.user_id')
        ->where('users.id', $userId)
        ->select('users.*', 'user_details.*')
        ->first();

        session([            
            'user_name' => $otpData->full_name_en,
            'user_id' => $otpData->user_id
        ]);
        
        
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

        $mobile_no = session()->get('mobile_no');
        $otpData = sports_gradation_certificate::where('mobile_no', $mobile_no) // ✅ Corrected this line
        ->first();
        return view('viewAppliedCertificate')->with(['otpData' => $otpData]);
    }

    public function create()
    {
        return view('sports_registration_form');
    }

    public function store(Request $request)
    {      
        
        $request->validate([
            'name' => 'required|string'
        ]);
		
		$year1 = now()->year;
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $applId = $year1 . $random;

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

        $noc_upload = null; // Default value to prevent "Undefined variable" error

        if ($request->hasFile('noc_upload')) {
            $noc_upload = $request->file('noc_upload')->store('uploads', 'public');            
        }
        
        $year = date('y'); // Get last two digits of the year (e.g., 2025 -> 25)
        $districtCode = strtoupper(substr($request->district_sportsperson_belongs, 0, 2)); // First 2 letters of district
        $gameCode = strtoupper(substr($request->name_sports_discipline, 0, 2)); // First 2 letters of game

        $selectedState = $request->input('plays_for_statte_org');

        if ($selectedState === 'Other') {
            $state_name = $request->input('other_state_input');
            
        } else {
            $state_name = $selectedState;
        }

        // Generate a unique 5-character alphanumeric string
        $uniqueCode = strtoupper(substr(md5(uniqid()), 0, 5));

        $certificateNo = "GRAD-{$year}{$districtCode}{$gameCode}{$uniqueCode}";
        $user_id = session('user_id');        
        $user = sports_gradation_certificate::Create(
            
            [
            'certificate_no'=> $certificateNo,
            'sports_person_name' => $request->name,
            'aadhaar_no' => $request->aadhaar_no,
            'mobile_no' => $request->mobile_no,
            'district_sportsperson_belongs' => $request->district_sportsperson_belongs,
            'domicile_state' => $request->domicile_state,
            'plays_for_statte_org' => $state_name,
            'name_sports_discipline' => $request->name_sports_discipline,
            'tournament_name' => $request->tournament_name,
            'month_year' => $request->month_year,
            'venue_of_tournament' => $request->venue_of_tournament,
            'organising_authority' => $request->organising_authority,
            'tournament_type' => $request->tournament_type,
            'medal_won' => $request->medal_won,
            'participation_level' => $request->participation_level,
            'profile_picture' => $imagePath,
            'noc_upload' => $noc_upload,
            'aadhaar_card' => $aadhaar_card,
            'domicile_certificate' => $domicile_certificate,
            'sports_certificate' => $sports_certificate,
            'more_than25_photo' => $more_than25_photo,
            'type_of_event' => $request->type_of_event,
            'terms_conditions' => $request->terms_conditions,
            'user_id' => $user_id,
			'appl_id' => $applId,
            'date' => \Carbon\Carbon::now('Asia/Kolkata')

        ]);

        session(['last_inserted_id' => $user->id]);

        return redirect()->route('verification.by.sportsperson',['id' => $user->id]);

        //return redirect()->back()->with('success', 'Registration Successful!');
    }

    public function uploadFile(Request $request)
    {  
        $user_id = session('user_id'); 

        $imagePath = null; // Default value to prevent "Undefined variable" error

        if ($request->hasFile('VerificationFile')) {
            $imagePath = $request->file('VerificationFile')->store('uploads', 'public');            
        }

        $applId = $request->applId;

        $data = sports_gradation_certificate::updateOrCreate(
            [
                'id' => $applId,
                'user_id' => $user_id
            ],
            [
                'verification_by_sportsperson' => $imagePath
            ]
        );        

        return redirect()->route('verification.by.sportsperson',['id' => $applId]);
    }

    public function verifyStatusSubmit(Request $request)
    {
        $user_id = session('user_id');        

        $applId = $request->applId;
        $status = $request->status;

        $data = sports_gradation_certificate::updateOrCreate(
            [
                'id' => $applId,
                'user_id' => $user_id
            ],
            [
                'verify_status' => $status
            ]
        );        

        return redirect()->route('dashboard');
    }

    public function fileRemove(Request $request)
    {
        $user_id = session('user_id');
        $request->validate([
            'applId' => 'required|integer'
        ]);

        $record = sports_gradation_certificate::where('id', $request->applId)
                    ->where('user_id', $user_id)
                    ->first();

        if ($record && $record->verification_by_sportsperson) {
            $filePath = public_path('storage/' . $record->verification_by_sportsperson);
            if (file_exists($filePath)) {
                unlink($filePath); // delete file from server
            }

            $record->update(['verification_by_sportsperson' => null]);
        }

        return redirect()->back()->with('success', 'File removed successfully.');
    }


    public function verificationBySportsPerson($id)
    {  

        $user_id = session('user_id'); 

        $otpData = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
        ->where('sports_gradation_certificates.id', $id)
        ->where('sports_gradation_certificates.user_id', $user_id)        
        ->orderBy('sports_gradation_certificates.created_at', 'desc')
        ->select('sports_gradation_certificates.*', 'category_wise_gradations.gradation', 'category_wise_gradations.tournament', 'category_wise_gradations.organising_authority as authority')
        ->first();
        
        return view('verification_by_sportperson')->with(['otpData' => $otpData]);
    }

    public function logout(Request $request)
    {
        session()->flush();
        session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }
    
}
