<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SportsKitRequisition;
use App\Models\sports_gradation_certificate;
;

class DSOController extends Controller
{
    public function index()
    {
        // Fetch sports requests with their HQ verification status
        $sportsRequests = SportsKitRequisition::with('hqSportsRequest')->get();

        return view('dso.sports_requests_list', compact('sportsRequests'));
    }

    public function dashboard() {
        // Fetch total application count
        $totalApplications = SportsKitRequisition::count();

        // Fetch total approved applications
        $totalApproved = SportsKitRequisition::where('status', 'Approved')->count();

        // Fetch total rejected applications
        $totalRejected = SportsKitRequisition::where('status', 'Rejected')->count();
        return view('dso.dashboard', compact('totalApplications', 'totalApproved', 'totalRejected'));
    }

    public function grad_list(){
        //return "hi";
         // Fetch total application count
        $sportsCertificates = sports_gradation_certificate::orderBy('created_at', 'desc')->get();
        return view('dso.grad_list', compact('sportsCertificates'));

    }
    public function create() {
        // return "hi";
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

  
   public function verifyRequest($id)
   {
    $request = SportsKitRequisition::find($id);

    if (!$request) {
        return redirect()->back()->with('error', 'Request not found!');
    }

    $request->verification_status = 'Verified';
    $request->status = 'Verified'; // Update status
    $request->verification_datetime = now();
    $request->save();

       return redirect()->back()->with('success', 'Request verified successfully!');
   }


   public function notVerifyRequest($id)
   {
    $request = SportsKitRequisition::find($id);

    if (!$request) {
        return redirect()->back()->with('error', 'Request not found!');
    }

       $request->verification_status = 'Not Verified';
       $request->status = 'Not Verified';
       $request->verification_datetime = now();
        $request->save();

       return redirect()->back()->with('error', 'Request marked as not verified!');
   }



}
