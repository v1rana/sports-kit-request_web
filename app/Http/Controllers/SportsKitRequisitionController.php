<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SportsKitRequisition;
use App\Models\ADC;
use App\Models\HQ;
use App\Models\DSO;
use App\Models\TemporarySportsKitRequisition;
use App\Models\UserDetails;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SportsKitRequisitionController extends Controller {
    
    public function dashboard() {
        // Fetch total application count
        $totalApplications = SportsKitRequisition::count();

        // Fetch total approved applications
        $totalApproved = SportsKitRequisition::where('status', 'Approved')->count();

        // Fetch total rejected applications
        $totalRejected = SportsKitRequisition::where('status', 'Rejected')->count();
        $totalPending = SportsKitRequisition::where('status', 'Pending')->count();
        $totalVerified = SportsKitRequisition::where('status', 'Verified')->count();
        $totalNotVerified = SportsKitRequisition::where('status', 'Not Verified')->count();
        $totalDisbursed = SportsKitRequisition::where('status', 'Disbursed')->count();

        return view('gm.dashboard', compact('totalApplications', 'totalApproved', 'totalRejected', 'totalPending', 'totalVerified', 'totalNotVerified', 'totalDisbursed'));
    }

    public function index()
    {
        $sportsRequests = SportsKitRequisition::with('hqSportsRequest')->get();

        return view('gm.sports_requests_list', compact('sportsRequests'));
    }
    
    // Show the requisition form
  public function create($user_id)
{
    try {
        $userId = Crypt::decryptString($user_id);
        session([
            'user_id' => $userId,
        ]);
    } catch (\Exception $e) {
        abort(403, 'Invalid or tampered ID.');
    }
    $userId = session('user_id'); // Assuming user ID is stored in session
    if(empty($userId)){
        $userId ='1';
        $_SESSION['user_id'] = '1';
    }
	//return $userId;
    // Get the user details
    $userDetail = UserDetails::where('user_id', $userId)->first();

	
	// return $userDetail->area_name;
     $tempEntry = TemporarySportsKitRequisition::where([
        ['district', '=', $userDetail->district],
        ['block', '=', $userDetail->block_town],
        ['area_name', '=', $userDetail->ward_village]
    ])->first();

    if ($tempEntry) {
        // Store in session so it works with uploadform etc.
        session(['form_data' => $tempEntry->toArray(), 'temp_id' => $tempEntry->id]);
        return redirect()->route('sports-kit.print.temp');
    }

    // ✅ Second check: if a final submitted application exists
    $application = SportsKitRequisition::where([
        ['district', '=', $userDetail->district],
        ['block', '=', $userDetail->block_town],
        ['area_name', '=', $userDetail->ward_village]
    ])->latest()->first();

    if ($application) {
        return view('sports_kit.status', [
            'application' => $application,
            'userDetail' => $userDetail
        ]);
    }

    // If no application, show the requisition form
    return view('sports_kit.requisition', [
        'userDetail' => $userDetail
    ]);
}



    // Store the requisition request
    public function store(Request $request) {
		
		// return "hi";
		// Validate the request
$validatedData = $request->validate([
    'name' => 'required|string|max:100',
    'district' => 'required|string|max:100',
    'block' => 'required|string|max:100',
    'area_name' => 'required|string|max:100',
    'designation' => 'required|string|max:50',
    'specific_designation' => 'required|string|max:50',
    'sports_equipment' => 'required|array',
    'sports_equipment.*.name' => 'required|string',
    'sports_equipment.*.equipment' => 'required|string',
    'sports_equipment.*.quantity' => 'required|integer|min:1',
    'sports_equipment.*.fop_available' => 'required|string|max:50',
    'sports_equipment.*.players_count' => 'required|integer|min:1',
    'sports_equipment.*.last_issued_date' => 'nullable|date',
    //'sports_equipment.*.date' => 'date',
    'sports_equipment.*.photo' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048'
]);
// echo "<pre>";
// print_r($_POST);

// Process equipment photos and data
// Initialize an array to store the final equipment data
$finalEquipments = [];

// Loop through each equipment and process its data
foreach ($request->sports_equipment as $equipment) {
    $photoPath = null; // Initialize photoPath to null if no photo uploaded

    // Check if a photo was uploaded for this equipment
    if (isset($equipment['photo']) && $equipment['photo'] instanceof \Illuminate\Http\UploadedFile) {
        // Generate a unique filename to prevent overwriting files
        $filename = uniqid() . '_' . $equipment['photo']->getClientOriginalName();
        
        // Move the uploaded photo to the public/uploads directory
        $equipment['photo']->move(public_path('assets/uploads'), $filename);
        
        // Store the photo path for this equipment
        $photoPath = 'assets/uploads/' . $filename;
    }

    // Store the equipment data, including the photo path (if any)
    $finalEquipments[] = [
        'name' => $equipment['name'],
        'equipment' => $equipment['equipment'],
        'quantity' => $equipment['quantity'],
        'photo' => $photoPath, // Store the photo path (null if no photo uploaded)
        'players_count' => $equipment['players_count'],
        'last_issued_date' => $equipment['last_issued_date'],
        'fop_available' => $equipment['fop_available']
    ];
}

// Encode equipment data as JSON
$encodedEquipments = json_encode($finalEquipments);
// return $encodedEquipments;
// Check for duplicate
$existing = SportsKitRequisition::where([
    ['district', '=', $validatedData['district']],
    ['block', '=', $validatedData['block']],
    ['area_name', '=', $validatedData['area_name']],
])->where('sports_equipment', $encodedEquipments)->first();

if ($existing) {
    return redirect('/sports-kit')->with('warning', 'You have already submitted this form.');
}

// Save to database
$kit = SportsKitRequisition::create([
    'applicant_id' => 'TEMP', // Temporary applicant ID before updating
    'name' => $validatedData['name'],
    'district' => $validatedData['district'],
    'block' => $validatedData['block'],
    'area_name' => $validatedData['area_name'],
    'designation' => $validatedData['designation'],
    'specific_designation' => $validatedData['specific_designation'],
    'sports_equipment' => $encodedEquipments,
    'sports_photos' => null,
	'fop_available' => '',
    'players_count' => 0,
    'last_issued_date' => null,
    'status' => 'Pending'
]);

// Generate applicant ID with padded number
$kit->applicant_id = 'SKIT-' . str_pad($kit->id, 8, '0', STR_PAD_LEFT);

// Save the updated applicant ID
$kit->save();

// Return the success view with the kit data
return view('sports_kit.print', compact('kit'));
}

	
	public function print($id)
{
    $kit = SportsKitRequisition::findOrFail($id);
    return view('sports_kit.print', compact('kit'));
}
    

    public function list() {
        
        $sportsRequests = SportsKitRequisition::orderBy('created_at', 'desc')->get(); 
        $vendors = Vendor::orderBy('created_at', 'desc')->get(); 
        //return $vendors;
        return view('sports_kit.list', compact('sportsRequests','vendors'));
   }

   public function storeVendorAssignment(Request $request) {
    $request->validate([
        'request_id' => 'required|exists:sports_kit_requisitions,id',
        'vendor_id' => 'required|exists:vendors,id',
    ]);

    // Find the sports requisition request
    $sportsRequest = SportsKitRequisition::findOrFail($request->request_id);

    // Assign the vendor
    $sportsRequest->vendor_id = $request->vendor_id;
    $sportsRequest->vendor_assigned_date = now();
    $sportsRequest->save();

    return redirect()->back()->with('success', 'Vendor assigned successfully!');
}

    public function uploadform(Request $request)
    {
		if (!$request->hasFile('signed_document') || !session()->has('form_data')) {
			return redirect()->back()->withErrors('Missing signed document or session data.');
		}

		$formData = session('form_data');
		$tempId = session('temp_id');

		$destinationPath = public_path('uploads/gram_municipal_signed_document');
		if (!file_exists($destinationPath)) {
			mkdir($destinationPath, 0755, true);
		}
		
		$file = $request->file('signed_document');
		$docFilename = uniqid('gram_doc_') . '_' . $file->getClientOriginalName();
		$file->move(public_path('uploads/gram_municipal_signed_document'), $docFilename);


		// ✅ Check duplicate
		$encodedEquipments = is_array($formData['sports_equipment'])
        ? json_encode($formData['sports_equipment'])
        : $formData['sports_equipment'];
		$existing = SportsKitRequisition::where([
			['district', '=', $formData['district']],
			['block', '=', $formData['block']],
			['area_name', '=', $formData['area_name']],
		])->where('sports_equipment', $encodedEquipments)->first();

		if ($existing) {
			return redirect('/registration-form/1')->with('warning', 'You have already submitted this form.');
		}
		
		if (!isset($formData['applicant_id'])) {
			return redirect('/registration-form/1')->with('error', 'Missing application ID. Please refill the form.');

		}

		// ✅ Save to DB
		$kit = SportsKitRequisition::create([
			'applicant_id' => $formData['applicant_id'],
			'name' => $formData['name'],
			'district' => $formData['district'],
			'block' => $formData['block'],
			'area_name' => $formData['area_name'],
			'designation' => $formData['designation'],
			'specific_designation' => $formData['specific_designation'],
			'sports_equipment' => $encodedEquipments,
			'gram_municipal_signed_document' => $docFilename,
			'status' => 'Pending'
		]);

		//$kit->applicant_id = 'SKIT-' . str_pad($kit->id, 8, '0', STR_PAD_LEFT);
		$kit->save();

		// Delete from temporary table
		TemporarySportsKitRequisition::find($tempId)?->delete();

		session()->forget(['form_data', 'temp_id']);

		 $userDetail = UserDetails::where('user_id', '1')->first();
		$application = SportsKitRequisition::where([
			['district', '=', $userDetail->district],
			['block', '=', $userDetail->block_town],
			['area_name', '=', $userDetail->ward_village]
		])->latest()->first();

		if ($application) {
			return view('sports_kit.status', [
				'application' => $application,
				'userDetail' => $userDetail,
				'successMessage' => 'Form submitted successfully.'
			]);
		}
    }
	
	public function logout(){
		session()->flush(); // This clears all session data
    // auth()->logout();   // This logs the user out
		return redirect('/');
	}
	
	public function sendOTP(Request $request){
		
		$request->validate([
			'mobile' => 'required|digits:10',
		]);

		$mobile = $request->mobile;

    // Check which type of user is trying to login

        $user = DSO::where('mob', $mobile)->first();
        $role = 'DSO';
    
		if (!$user) {
			$user = ADC::where('mob', $mobile)->first();
			$role = 'ADC';
		}
		if (!$user) {
			$user = HQ::where('mob', $mobile)->first();
			$role = 'HQ';
		}

		if (!$user) {
			return back()->withErrors(['mobile' => 'Mobile number not registered.']);
		}

		// Generate OTP
		$otp = rand(100000, 999999);
		$expiryTime = now()->addMinutes(10);

		// Store OTP in user record
		$user->update([
			'otp' => $otp,
			'expires_at' => $expiryTime
		]);

		// SMS configuration
		$username = 'haryanait-sport';
		$password = 'sports@1234';
		$senderid = 'GOVHRY';       
		$dept_key = 'dca7fc77-9e28-4765-bbaa-07bd43197b2e';
		$temp_id  = '1407174538599726119';

		$msg = "Dear $role User, $otp is OTP for Login. Sports Department, Haryana";

		$encryp_password = sha1(trim($password));
		$msgidi = $this->sendSingleSMS($username, $encryp_password, $senderid, $msg, $mobile, $dept_key, $temp_id);

		return response()->json([
			'success' => true,
			'message' => 'OTP sent successfully'
		]);
}

public function verifyOTP(Request $request)
{
    $request->validate([
        'mobile' => 'required|digits:10',
        'otp' => 'required|digits:6'
    ]);

   return $mobile = $request->mobile;
    $otp = $request->otp;

    // Check in all user tables
    $user = DSO::where('mob', $mobile)->first()
        ?? ADC::where('mob', $mobile)->first()
        ?? HQ::where('mob', $mobile)->first();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Mobile number not registered.']);
    }

    if ($user->otp != $otp || now()->gt($user->otp_expires_at)) {
        return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.']);
    }

    // Clear OTP after verification
    $user->update([
        'otp' => null,
        'expires_at' => null
    ]);

    // Set session or login logic here if needed

    // Determine redirect route based on user role/table
    if ($user instanceof DSO) {
        $redirectTo = route('dso.sports_kit.dashboard');
    } elseif ($user instanceof ADC) {
        $redirectTo = route('adc.sports_kit.dashboard');
    } elseif ($user instanceof HQ) {
        $redirectTo = route('hq.sports_kit.dashboard');
    } else {
        $redirectTo = '/login'; // fallback
    }

    return response()->json([
        'success' => true,
        'message' => 'OTP verified successfully',
        'redirect_to' => $redirectTo
    ]);
}


	
				
	//Function to send single sms
	public function sendSingleSMS($username,$encryp_password,$senderid,$message,$mobileno,$deptSecureKey,$temp_id){
		$key=hash('sha512',trim($username).trim($senderid).trim($message).trim($deptSecureKey));
		// $key=hash('sha512',trim($username).trim($senderid).trim($message));

		$data = array(
			"username" => trim($username),
			"password" => trim($encryp_password),
			"senderid" => trim($senderid),
			"content" => trim($message),
			"smsservicetype" =>"otpmsg",
			"mobileno" =>trim($mobileno),
			"key" => trim($key),
			"templateid" => trim($temp_id)
		);
		return $a = $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT",$data); //calling post_to_url to send sms
	}
			
	public function post_to_url($url, $data) {
		
		$fields = '';
		
		foreach($data as $key => $value) {
			$fields .= $key . '=' . $value . '&';
		}

		$fields = rtrim($fields, '&');

		$post = curl_init();
		curl_setopt($post, CURLOPT_SSLVERSION, 6);
		curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($post, CURLOPT_URL, $url);
		curl_setopt($post, CURLOPT_POST, TRUE);
		curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($post);

		curl_close($post);		
		return $result;
		
	}
	
}
