<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HQ;
use App\Models\HQSportsRequest;
use App\Models\SportsKitRequisition;
use App\Models\sports_gradation_certificate;
use App\Models\Vendor;
use App\Models\Sport;
use App\Models\EquipmentVendorAssignment;

class HQController extends Controller
{
    // Show all sports requisition requests
    public function index()
    {
        $sportsRequests = SportsKitRequisition::with('hqSportsRequest')
        ->where('status', 'Approved') // Only fetch verified requests
        ->get();

        $vendors = Vendor::all();

        return view('hq.sports_requests_list', compact('sportsRequests','vendors'));
    }

    public function dashboard() {
       // Fetch total application count
       $totalApplications = SportsKitRequisition::count() + sports_gradation_certificate::join(
        'category_wise_gradations',
        'sports_gradation_certificates.tournament_name',
        '=',
        'category_wise_gradations.id'
    )
    ->whereIn('category_wise_gradations.gradation', ['C', 'D'])
    ->count();

    $totalsportsCertificatesCount = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
->whereIn('category_wise_gradations.gradation', ['C', 'D'])
->count();

      // Fetch total approved applications
      $totalApproved = SportsKitRequisition::where('status', 'Approved')->count();

      // Fetch total rejected applications
      $totalRejected = SportsKitRequisition::where('status', 'Rejected')->count();
      $totalPending = SportsKitRequisition::where('status', 'Pending')->count();
      $totalVerified = SportsKitRequisition::where('status', 'Verified')->count();
      $totalNotVerified = SportsKitRequisition::where('status', 'Not Verified')->count();
      $totalDisbursed = SportsKitRequisition::where('status', 'Disbursed')->count();

        return view('hq.dashboard', compact('totalApplications','totalsportsCertificatesCount', 'totalApproved', 'totalRejected', 'totalPending', 'totalVerified', 'totalNotVerified', 'totalDisbursed'));
    }

    // Assign HQ to a sports requisition request
    public function assignVendor(Request $request)
{
     $validated = $request->validate([
        'request_id' => 'required|exists:sports_kit_requisitions,id',
        'equipment_name' => 'required|string|max:255',
        'vendor_id' => 'required|exists:vendors,id',
    ]);

    EquipmentVendorAssignment::updateOrCreate(
        [
            'request_id' => $validated['request_id'],
            'equipment_name' => $validated['equipment_name'],
        ],
        [
            'vendor_id' => $validated['vendor_id'],
        ]
    );

    return redirect()->back()->with('success', 'Vendor assigned successfully!');
}

	public function hosp_requests()
    {
       /*  $sportsRequests = SportsKitRequisition::with('hqSportsRequest')
        ->where('status', 'Approved') // Only fetch verified requests
        ->get();

        $vendors = Vendor::all(); */

        return view('hq.hosp_requests_list');
    }
	
	public function vendor_form()
    {
	
		// Fetch all vendors from the database
		$sports = Sport::all();
		
        return view('hq.vendor_form', compact('sports'));
    }
	
	public function vendor_list()
    {
	
		// Fetch all vendors from the database
		 $vendors = Vendor::with('sports')->get();
		// return $vendors;
        return view('hq.vendor_list', compact('vendors'));
    }
	
	public function create(){
    
		return view('hq.vendor_form'); // This view contains the vendor form
	}

	public function store(Request $request)
{
    // Validate vendor, games, and document upload
    $validatedData = $request->validate([
        'firm_name' => 'required|string|max:255',
        'owner_name' => 'required|string|max:255',
        'pan_number' => 'required|string|max:20',
        'firm_address' => 'required|string|max:255',
        'district' => 'required|string|max:100',
        'pincode' => 'required|string|max:10',
        'games' => 'required|array',
        'games.*.game' => 'required|integer|exists:sports,id',
        'games.*.rate' => 'required|numeric',
        'games.*.photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'document_upload' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    // Save vendor
    $vendor = Vendor::create([
        'vendor_name' => $validatedData['firm_name'],
        'owner_name' => $validatedData['owner_name'],
        'pan_of_owner' => $validatedData['pan_number'],
        'firm_address' => $validatedData['firm_address'],
        'district' => $validatedData['district'],
        'pincode' => $validatedData['pincode'],
    ]);

    // Save document upload
    $docFile = $request->file('document_upload');
    $docFilename = $docFile->getClientOriginalName();
    $docFile->move(public_path('uploads/vendor_assigned_documents'), $docFilename);

    // Optional: Save document filename in vendor table (add a column like `document`)
    $vendor->update(['vendor_assigned_document' => $docFilename]);

    // Attach games
    foreach ($validatedData['games'] as $index => $game) {
        $photoFilename = null;

        if ($request->hasFile("games.$index.photo")) {
            $uploadedFile = $request->file("games.$index.photo");
            $photoFilename = $uploadedFile->getClientOriginalName();
            $uploadedFile->move(public_path('uploads/games'), $photoFilename);
        }

        $vendor->sports()->attach($game['game'], [
            'rate' => $game['rate'],
            'photo' => $photoFilename,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return redirect()->route('hq.vendor')->with('success', 'Vendor added successfully.');
}



}
