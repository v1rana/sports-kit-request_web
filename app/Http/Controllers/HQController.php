<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HQ;
use App\Models\HQSportsRequest;
use App\Models\SportsKitRequisition;
use App\Models\sports_gradation_certificate;
use App\Models\Vendor;
use App\Models\Sport;
use App\Models\User;
use App\Models\EquipmentVendorAssignment;
use Illuminate\Support\Facades\DB;

class HQController extends Controller
{
    // Show all sports requisition requests
    public function index()
    {
        $sportsRequests = SportsKitRequisition::with('hqSportsRequest')->where('status', 'Approved')->get();
		$vendors = Vendor::all();
// return $sportsRequests;
		// Attach status to each request
		foreach ($sportsRequests as $request) {
			$equipmentList = is_array($request->sports_equipment)
				? $request->sports_equipment
				: json_decode($request->sports_equipment, true);

			$total = is_array($equipmentList) ? count($equipmentList) : 0;

			 // Try to auto-assign vendors
			 $sport = Sport::where('sports_name', $request->sport_name)->first();

			 foreach ($equipmentList as $equipment) {
				 $alreadyAssigned = EquipmentVendorAssignment::where('request_id', $request->id)
					 ->where('equipment_name', $equipment['name'])
					 ->exists();
	 
				 if (!$alreadyAssigned && $sport) {
					 $vendorMatch = DB::table('sport_vendor')
						 ->join('vendors', 'sport_vendor.vendor_id', '=', 'vendors.id')
						 ->where('sport_vendor.sport_id', $sport->id)
						 ->where('sport_vendor.equipment', $equipment['name'])
						 ->select('vendors.id')
						 ->first();
	 
					 if ($vendorMatch) {
						 EquipmentVendorAssignment::create([
							 'request_id' => $request->id,
							 'equipment_name' => $equipment['name'],
							 'vendor_id' => $vendorMatch->id,
						 ]);
					 }
				 }
			 }
			$assigned = EquipmentVendorAssignment::where('request_id', $request->id)->count();

			if ($assigned === $total && $total > 0) {
				$request->vendor_status = 'Vendors Assigned';
			} elseif ($assigned > 0) {
				$request->vendor_status = 'Partially Disbursed';
			} else {
				$request->vendor_status = 'Not Assigned';
			}
		}

		return view('hq.sports_requests_list', compact('sportsRequests', 'vendors'));
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
->whereIn('category_wise_gradations.gradation', ['A', 'B'])
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
      $users = User::with(['userDetails', 'sportsDisciplineHosp', 'declarationsHosp'])->paginate(10);
		// dd($users->userDetails());
        return view('hq.hosp_requests_list', compact('users'));
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
	
    // Validate basic fields first
    $validatedData = $request->validate([
        'firm_name' => 'required|string|max:255',
        'owner_name' => 'required|string|max:255',
        'pan_number' => 'required|string|max:20',
        'firm_address' => 'required|string|max:255',
        'district' => 'required|string|max:100',
        'pincode' => 'required|string|max:10',
        'games' => 'required|array',
        'games.*.game' => 'required|integer|exists:sports,id',
        'games.*.equipment' => 'required|string|max:100',
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
            'equipment' => $game['equipment'], // <-- make sure you add this column in pivot table
            'rate' => $game['rate'],
            'photo' => $photoFilename,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
// return "hi";
    return redirect()->route('hq.vendor')->with('success', 'Vendor added successfully.');
}


	public function grad_list(){
       
         $sportsCertificates = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
         ->whereIn('category_wise_gradations.gradation', ['A', 'B'])
		 ->where('sports_gradation_certificates.verification_by_sportsperson', '!=', '')
		->where('sports_gradation_certificates.verify_status', '!=', '')
         ->orderBy('sports_gradation_certificates.created_at', 'desc')
         ->select('sports_gradation_certificates.*', 'category_wise_gradations.gradation', 'category_wise_gradations.tournament', 'category_wise_gradations.organising_authority as authority')
         ->get();

         // Format Month-Year after fetching results
		foreach ($sportsCertificates as $certificate) {
			if (!empty($certificate->month_year)) {
				$certificate->formatted_month_year = \Carbon\Carbon::createFromFormat('Y-m-d', $certificate->month_year)->format('F Y');
			} else {
				$certificate->formatted_month_year = 'N/A';
			}
		}

        return view('hq.grad_list_A_B', compact('sportsCertificates'));

    }
	
	public function ApproveRequest($id)
   {
    $request = sports_gradation_certificate::find($id);

    if (!$request) {
        return redirect()->back()->with('error', 'Request not found!');
    }

    $request->status = 'Approved';
    //$request->status = 'Verified'; // Update status
    $request->approve_reject_datetime = \Carbon\Carbon::now('Asia/Kolkata');
    $request->save();
	
	$result = DB::table('user_details')
		->join('users', 'user_details.user_id', '=', 'users.id')
		->select('user_details.user_id', 'users.mobile')
		->first();
		
		// SMS configuration
		$username = config('sms.username');
		$password = config('sms.password');
		$senderid = config('sms.senderid');
		$dept_key = config('sms.dept_key');
		$temp_id  = config('sms.temp_id2');

		$status= $request->status;
		$scheme= 'Haryana Sports Equipment';
		$app_id= $request->appl_id;
		$mobile= $result->mobile;
		$msg = "Dear User, your status for application $app_id for $scheme is $status. Sports Department, Haryana";

		$encryp_password = sha1(trim($password));
		$msgidi = $this->sendSingleSMS($username, $encryp_password, $senderid, $msg, $mobile, $dept_key, $temp_id);

       return redirect()->back()->with('success', 'Request Approved successfully!');
   }

   public function RejectRequest(Request $request,$id)
   {
    $certificate = sports_gradation_certificate::find($id);

    if (!$certificate) {
        return redirect()->back()->with('error', 'Request not found!');
    }

    $certificate->status = 'Rejected';
    $certificate->rejection_remarks = $request->rejection_remark;
    $certificate->approve_reject_datetime = \Carbon\Carbon::now('Asia/Kolkata');
    $certificate->save();
	
	$result = DB::table('user_details')
		->join('users', 'user_details.user_id', '=', 'users.id')
		->select('user_details.user_id', 'users.mobile')
		->first();
		
		// SMS configuration
		$username = config('sms.username');
		$password = config('sms.password');
		$senderid = config('sms.senderid');
		$dept_key = config('sms.dept_key');
		$temp_id  = config('sms.temp_id2');

		$status= $certificate->status;
		$scheme= 'Haryana Sports Equipment';
		$app_id= $certificate->appl_id;
		$mobile= $result->mobile;
		$msg = "Dear User, your status for application $app_id for $scheme is $status. Sports Department, Haryana";

		$encryp_password = sha1(trim($password));
		$msgidi = $this->sendSingleSMS($username, $encryp_password, $senderid, $msg, $mobile, $dept_key, $temp_id);

       return redirect()->back()->with('success', 'Request Rejected successfully!');
   }
   
  public function viewAppliedCertificate(Request $request)
{
    // Validate POST input
    $request->validate([
        'certificate_id' => 'required|integer|exists:sports_gradation_certificates,id',
    ]);

    $id = $request->certificate_id;

    $otpData = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
        ->where('sports_gradation_certificates.id', $id)
        ->whereIn('category_wise_gradations.gradation', ['A', 'B'])
        ->orderBy('sports_gradation_certificates.created_at', 'desc')
        ->select(
            'sports_gradation_certificates.*',
            'category_wise_gradations.gradation',
            'category_wise_gradations.tournament',
            'category_wise_gradations.organising_authority as authority'
        )
        ->first();

    if (!$otpData) {
        abort(404, 'Certificate not found or invalid gradation.');
    }

    return view('hq.viewAppliedCertificate', compact('otpData'));
}
   
   public function UploadCertificate(Request $request)
	{
		$request->validate([
			'certificate_pdf' => 'required|mimes:pdf|max:2048',
			'certificate_id' => 'required|exists:sports_gradation_certificates,id'
		]);

		// Upload file
		$file = $request->file('certificate_pdf');
		$filePath = $file->store('uploads/certificates', 'public');

		// Save to database
		$certificate = sports_gradation_certificate::find($request->certificate_id);
		$certificate->certificate_upload_datetime = \Carbon\Carbon::now('Asia/Kolkata');
		$certificate->certificate_pdf = $filePath;
		$certificate->save();

		return back()->with('success', 'Certificate uploaded successfully.');
	}
	
	public function UploadLetter(Request $request)
	{
		//return $request->all();
		 \Log::info('Request Data:', $request->all());
		$request->validate([
			'enquiry_pdf' => 'required|mimes:pdf|max:2048',
			'certificate_id' => 'required|exists:sports_gradation_certificates,id'
		]);

		// Upload file
		$file = $request->file('enquiry_pdf');
		$filePath = $file->store('uploads/enquiries/hq', 'public');

		//return $filePath;
		// Save to database
		$certificate = sports_gradation_certificate::find($request->certificate_id);
		$certificate->enquiry_pdf = $filePath;
		$certificate->enquiry_pdf_datetime = \Carbon\Carbon::now('Asia/Kolkata');
		$certificate->save();

		return back()->with('success', 'Letter uploaded successfully.');
	}
	
	public function RepliedLetter(Request $request)
	{
		$request->validate([
			'replied_pdf' => 'required|mimes:pdf|max:2048',
			'certificate_id' => 'required|exists:sports_gradation_certificates,id'
		]);

		// Upload file
		$file = $request->file('replied_pdf');
		$filePath = $file->store('uploads/enquiries/hq', 'public');

		// Save to database
		$certificate = sports_gradation_certificate::find($request->certificate_id);
		$certificate->replied_pdf = $filePath;
		$certificate->replied_pdf_datetime = \Carbon\Carbon::now('Asia/Kolkata');
		$certificate->save();

		return back()->with('success', 'Letter uploaded successfully.');
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


	public function approveOspRequest(Request $request,$id)
{
    $user = User::find($id);

    if (!$request) {
        return redirect()->back()->with('error', 'Request not found!');
    }

    $user->status =  $request->status;
    $user->save();
	$msg = $request->status == '1' ? 'Request approved successfully!' : 'Request rejected successfully!';
    return redirect()->back()->with('success', $msg);
}

public function rejectOspRequest(Request $req,$id)
{
    $request = SportsKitRequisition::find($id);

    if (!$request) {
        return redirect()->back()->with('error', 'Request not found!');
    }

    $request->approval_status = 'Rejected';
    $request->status = 'Rejected';
	$request->reject_remarks = $req->reject_remark;
    $request->approval_rejection_datetime = now();
    $request->save();
	
	$result = DB::table('user_details')
		->join('users', 'user_details.user_id', '=', 'users.id')
		->where('user_details.district', $request->district)
		->select('user_details.user_id', 'users.mobile')
		->first();
		
		// SMS configuration
		$username = config('sms.username');
		$password = config('sms.password');
		$senderid = config('sms.senderid');
		$dept_key = config('sms.dept_key');
		$temp_id  = config('sms.temp_id2');

		$status= $request->status;
		$scheme= 'Haryana Sports Equipment';
		$app_id= $request->applicant_id;
		$mobile= $result->mobile;
		$msg = "Dear User, your status for application $app_id for $scheme is $status. Sports Department, Haryana";

    return redirect()->back()->with('error', 'Request rejected successfully!');
}


}
