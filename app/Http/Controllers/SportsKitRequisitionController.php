<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SportsKitRequisition;
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

    // Check if application exists for the user (example location-based logic)
    $application = SportsKitRequisition::where('district', 'FARIDABAD')
        ->where('block', 'TIGAON BL')
        ->where('area_name', 'Faridpur')
        ->latest()
        ->first();

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
		// Handle the file upload
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $file->store('uploads'); // Store file logic

        // Save first form data temporarily in session
        session([
            'show_second_form' => true,
            'first_form_data' => $request->only([
                'name',
                'district',
                'block',
                'designation',
                'specific_designation',
                'area_name',
                'declaration_place',
                'declaration_signature',
                'declaration_date',
            ])
        ]);

        return redirect()->back()->with('success', 'Form uploaded successfully.');
    }

    return redirect()->back()->withErrors('File upload failed.');
    }
	
	public function logout(){
		session()->flush(); // This clears all session data
    // auth()->logout();   // This logs the user out
		return redirect('/');
	}
	
}
