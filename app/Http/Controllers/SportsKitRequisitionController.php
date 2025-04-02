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
    public function create() {
        
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
            'sports_photos' => 'nullable|array',
            'sports_photos.*.date' => 'required_with:sports_photos|string',
            'sports_photos.*.photo' => 'sometimes|file|image|mimes:jpg,jpeg,png|max:2048',
            'fop_available' => 'required|string|max:50',
            'players_count' => 'required|integer|min:1',
            'last_issued_date' => 'nullable|date'
        ]);
        
        // Handle Image Uploads
        $photoPaths = [];
        if ($request->hasFile('sports_photos')) {
            $sportsPhotos = (array) $request->sports_photos; // Ensure it's an array
            foreach ($sportsPhotos as $key => $photoData) {
                if (isset($photoData['photo']) && isset($photoData['date'])) {
                    $photoPaths[] = [
                        'date' => $photoData['date'],
                        'photo' => $photoData['photo']->store('uploads/photos', 'public')
                    ];
                }
            }
        }
        
        // Store Data
        SportsKitRequisition::create([
            'applicant_id' => '1',
            'district' => $validatedData['district'],
            'block' => $validatedData['block'],
            'area_name' => $validatedData['area_name'],
            'designation' => $validatedData['designation'],
            'sports_equipment' => json_encode((array) $validatedData['sports_equipment']),
            'sports_photos' => !empty($photoPaths) ? json_encode($photoPaths) : null,
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
