<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SportsKitRequisition;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;

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
        // Fetch sports requests with their HQ verification status
        $sportsRequests = SportsKitRequisition::with('hqSportsRequest')->get();

        return view('gm.sports_requests_list', compact('sportsRequests'));
    }
    
    // Show the requisition form
    public function create()
{
    $userId = session('user_id'); // Assuming user ID is stored in session

    // Check if application exists for the user
    $application = SportsKitRequisition::where('district', 'BHIWANI')
    ->where('block', 'KAIRU')
    ->where('area_name', 'BABARWAS')
    ->latest()
    ->first();

    if ($application) {
        
        return view('sports_kit.status', ['application' => $application]);
    }

    // If no application, show the requisition form
    return view('sports_kit.requisition');
}


    // Store the requisition request
    public function store(Request $request) {
        // Debugging: Log request data (optional, remove in production)
        \Log::info('Request Data:', $request->all());
        
        // Validate the request
        $validatedData = $request->validate([
            'district' => 'required|string|max:100',
            'block' => 'required|string|max:100',
            'area_name' => 'required|string|max:100',
            'designation' => 'required|string|max:50',
            'sports_equipment' => 'required|array|min:1',
            'sports_equipment.*.name' => 'required|string',
            'sports_equipment.*.equipment' => 'required|string',
            'sports_equipment.*.quantity' => 'required|integer|min:1',
            'sports_equipment.*.date' => 'required|date',
            'sports_equipment.*.photo' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
            'fop_available' => 'required|string|max:50',
            'players_count' => 'required|integer|min:1',
            'last_issued_date' => 'nullable|date'
        ]);
        
       // Process each equipment item
    $finalEquipments = [];

   foreach ($request->sports_equipment as $index => $equipment) {
    $photoPath = null;

    if (isset($equipment['photo']) && $equipment['photo'] instanceof \Illuminate\Http\UploadedFile) {
        $filename = time() . '_' . $equipment['photo']->getClientOriginalName();
        $equipment['photo']->move(public_path('assets/uploads'), $filename);
        $photoPath = 'assets/uploads/' . $filename;
    }

    $finalEquipments[] = [
        'name' => $equipment['name'],
        'equipment' => $equipment['equipment'],
        'quantity' => $equipment['quantity'],
        'date' => $equipment['date'],
        'photo' => $photoPath,
    ];
}
        
        // Store Data
        SportsKitRequisition::create([
            'applicant_id' => '1',
            'district' => $validatedData['district'],
            'block' => $validatedData['block'],
            'area_name' => $validatedData['area_name'],
            'designation' => $validatedData['designation'],
            'sports_equipment' => json_encode($finalEquipments),
			'sports_photos' => null, // Not used now
            'fop_available' => $validatedData['fop_available'],
            'players_count' => $validatedData['players_count'],
            'last_issued_date' => $validatedData['last_issued_date'],
            'status' => 'Pending'
        ]);
    
        return redirect('/sports-kit')->with('success', 'Request submitted successfully!');
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

   
}
