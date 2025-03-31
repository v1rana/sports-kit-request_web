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

        return view('gm.dashboard', compact('totalApplications', 'totalApproved', 'totalRejected', 'totalPending'));
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
// return $request->validate();exit;
        // $request->merge([
        //     'sports_equipment' => array_filter($request->input('sports_equipment', []), function ($equipment) {
        //         return isset($equipment['name'], $equipment['equipment'], $equipment['quantity']) 
        //             && !empty($equipment['name']) 
        //             && !empty($equipment['equipment']) 
        //             && !empty($equipment['quantity']);
        //     }),
        //     'sports_photos' => array_filter($request->input('sports_photos', []), function ($photo) {
        //         return isset($photo['date'], $photo['photo']) 
        //             && !empty($photo['date']) 
        //             && is_file($photo['photo']); // Ensure it's a file
        //     }),
        // ]);
        $request->validate([
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
            'sports_photos.*.photo' => 'required_with:sports_photos|file|image|mimes:jpg,jpeg,png|max:2048',
            'fop_available' => 'required|string|max:50',
            'players_count' => 'required|integer|min:1',
            'last_issued_date' => 'nullable|date'
        ]);
        
        // Handle Image Uploads
        $photoPaths = [];
        if ($request->hasFile('sports_photos')) {
            foreach ($request->sports_photos as $key => $photoData) {
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
            'district' => $request->district,
            'block' => $request->block,
            'area_name' => $request->area_name,
            'designation' => $request->designation,
            'sports_equipment' => json_encode((array) $request->sports_equipment), // Ensure it's an array before encoding
            'sports_photos' => !empty($photoPaths) ? json_encode($photoPaths) : null, // Store only if photos exist
            'fop_available' => $request->fop_available,
            'players_count' => $request->players_count,
            'last_issued_date' => $request->last_issued_date,
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
