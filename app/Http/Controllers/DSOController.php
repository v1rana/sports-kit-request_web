<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SportsKitRequisition;
use App\Models\sports_gradation_certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\Vendor;
use App\Models\EquipmentVendorAssignment;

class DSOController extends Controller
{
    public function index()
	{
		// dd(session()->all());
		$district = session('district');

		$sportsRequests = DB::table('sports_kit_requisitions')
			->leftJoin('equipment_vendor_assignments', 'sports_kit_requisitions.id', '=', 'equipment_vendor_assignments.request_id')
			->leftJoin('vendors', 'equipment_vendor_assignments.vendor_id', '=', 'vendors.id')
			->where('sports_kit_requisitions.district', $district) 
			->where('sports_kit_requisitions.status', 'Pending')
			->select(
				'sports_kit_requisitions.id as requisition_id', 'vendors.id as vend_id',
				DB::raw('equipment_vendor_assignments.*, vendors.*, sports_kit_requisitions.*')
			)
			->get();

		return view('dso.sports_requests_list', compact('sportsRequests'));
	}


    public function dashboard() {
		// dd(session()->all());
       
	   $district = session('district');
	   ////////////////////////////[START]///////////////////////////////////////////////////////////////////////
		////////////////////////////[SPORTS CERTIFICATE]/////////////////////////////////////////////////////
		////////////////////////////[START]/////////////////////////////////////////////////////////////////////
    
        $totalsportsCertificatesCount = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
		->whereIn('category_wise_gradations.gradation', ['C', 'D'])
		->where('sports_gradation_certificates.verification_by_sportsperson', '!=', '')
		->where('sports_gradation_certificates.verify_status', '!=', '')
		->where('sports_gradation_certificates.district_sportsperson_belongs', $district)
		->count();

        // Fetch total approved applications
        $totalgrad_c_d_Approved = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
		->whereIn('category_wise_gradations.gradation', ['C', 'D'])
		->where('sports_gradation_certificates.verification_by_sportsperson', '!=', '')
		->where('sports_gradation_certificates.verify_status', '!=', '')
		->where('sports_gradation_certificates.district_sportsperson_belongs', $district)
		->where('status', 'Approved')->count();
		
        $totalgrad_c_d_Rejected = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
		->whereIn('category_wise_gradations.gradation', ['C', 'D'])
		->where('sports_gradation_certificates.verification_by_sportsperson', '!=', '')
		->where('sports_gradation_certificates.verify_status', '!=', '')
		->where('sports_gradation_certificates.district_sportsperson_belongs', $district)
		->where('status', 'Rejected')->count();
		
        $totalgrad_c_d_Pending = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
		->whereIn('category_wise_gradations.gradation', ['C', 'D'])
		->where('sports_gradation_certificates.verification_by_sportsperson', '!=', '')
		->where('sports_gradation_certificates.verify_status', '!=', '')
		->where('sports_gradation_certificates.district_sportsperson_belongs', $district)
		->where('status', null)->count();
		
		////////////////////////////[START]///////////////////////////////////////////////////////////////////////
		////////////////////////////[SPORTS KIT REQUISITION]/////////////////////////////////////////////////////
		////////////////////////////[START]/////////////////////////////////////////////////////////////////////
		$totalApplications = SportsKitRequisition::where('district', $district)->count();
		
        $totalApproved = SportsKitRequisition::where('district', $district)->where('status', 'Approved')->count();
        $totalRejected = SportsKitRequisition::where('district', $district)->where('status', 'Rejected')->count(); 
        $totalPending = SportsKitRequisition::where('district', $district)->where('status', 'Pending')->count();
        $totalVerified = SportsKitRequisition::where('district', $district)->where('status', 'Verified')->whereNull('sports_kit_requisitions.disbursement_status')->count();
        $totalNotVerified = SportsKitRequisition::where('district', $district)->where('status', 'Not Verified')->count();
        $totalDisbursed = SportsKitRequisition::where('district', $district)->where('status', 'Verified')->where('disbursement_status', 'Completed')->count();
		
        return view('dso.dashboard', compact('totalApplications','totalsportsCertificatesCount', 'totalApproved', 'totalRejected', 'totalPending', 'totalVerified', 'totalNotVerified', 'totalDisbursed','totalgrad_c_d_Approved','totalgrad_c_d_Rejected','totalgrad_c_d_Pending'));
		
		// return view('dso.dashboard', compact('totalApplications','totalsportsCertificatesCount', 'totalPending', 'totalVerified', 'totalNotVerified', 'totalDisbursed','totalgrad_c_d_Approved','totalgrad_c_d_Rejected','totalgrad_c_d_Pending'));
    }
	
	public function kit_verified_list()
	{
		// dd(session()->all());
		$district = session('district');

		$sportsRequests = DB::table('sports_kit_requisitions')
			->leftJoin('equipment_vendor_assignments', 'sports_kit_requisitions.id', '=', 'equipment_vendor_assignments.request_id')
			->leftJoin('vendors', 'equipment_vendor_assignments.vendor_id', '=', 'vendors.id')
			->where('sports_kit_requisitions.district', $district) 
			->where('sports_kit_requisitions.status', 'Verified')
			->whereNull('sports_kit_requisitions.disbursement_status')
			->select(
				'sports_kit_requisitions.id as requisition_id', 'vendors.id as vend_id',
				DB::raw('equipment_vendor_assignments.*, vendors.*, sports_kit_requisitions.*')
			)
			->get();

		return view('dso.kit_verified_list', compact('sportsRequests'));
	}
	
	public function kit_disbursed_list()
	{
		// dd(session()->all());
		$district = session('district');

		$sportsRequests = DB::table('sports_kit_requisitions')
			->leftJoin('equipment_vendor_assignments', 'sports_kit_requisitions.id', '=', 'equipment_vendor_assignments.request_id')
			->leftJoin('vendors', 'equipment_vendor_assignments.vendor_id', '=', 'vendors.id')
			->where('sports_kit_requisitions.district', $district) 
			->where('sports_kit_requisitions.status', 'Verified') 
			->where('sports_kit_requisitions.disbursement_status', '=', 'Completed')
			->select(
				'sports_kit_requisitions.id as requisition_id', 'vendors.id as vend_id',
				DB::raw('equipment_vendor_assignments.*, vendors.*, sports_kit_requisitions.*')
			)
			->get();

		return view('dso.kit_disbursed_list', compact('sportsRequests'));
	}

    public function grad_list(){
		
		 $district = session('district');
		 // dd($district);
        //return "hi";
         // Fetch total application count
        $sportsCertificates = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
         ->whereIn('category_wise_gradations.gradation', ['C', 'D'])
		 ->where('sports_gradation_certificates.verification_by_sportsperson', '!=', '')
		->where('sports_gradation_certificates.verify_status', '!=', '')
		->whereNull('sports_gradation_certificates.status')
		->where('sports_gradation_certificates.district_sportsperson_belongs', $district)
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

        return view('dso.grad_list', compact('sportsCertificates'));

    }
	
	public function approved_list(){
		
		 $district = session('district');
        //return "hi";
         // Fetch total application count
        $sportsCertificates = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
         ->whereIn('category_wise_gradations.gradation', ['C', 'D'])
		 ->where('sports_gradation_certificates.verification_by_sportsperson', '!=', '')
		->where('sports_gradation_certificates.verify_status', '!=', '')
		->where('sports_gradation_certificates.status', '=', 'Approved')
		->whereNull('sports_gradation_certificates.certificate_pdf')
		->where('sports_gradation_certificates.district_sportsperson_belongs', $district)
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

        return view('dso.approved_grad_list', compact('sportsCertificates'));

    }
	
	public function rejected_list(){
		
		 $district = session('district');
        //return "hi";
         // Fetch total application count
        $sportsCertificates = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
         ->whereIn('category_wise_gradations.gradation', ['C', 'D'])
		 ->where('sports_gradation_certificates.verification_by_sportsperson', '!=', '')
		->where('sports_gradation_certificates.verify_status', '!=', '')
		->where('sports_gradation_certificates.status', '=', 'Rejected')
		->where('sports_gradation_certificates.district_sportsperson_belongs', $district)
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

        return view('dso.rejected_grad_list', compact('sportsCertificates'));

    }
	
	public function certificate_issued_list(){
		
		 $district = session('district');
        //return "hi";
         // Fetch total application count
        $sportsCertificates = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
         ->whereIn('category_wise_gradations.gradation', ['C', 'D'])
		 ->where('sports_gradation_certificates.verification_by_sportsperson', '!=', '')
		->where('sports_gradation_certificates.verify_status', '!=', '')
		->where('sports_gradation_certificates.status', '=', 'Approved')
		->whereNotNull('sports_gradation_certificates.certificate_pdf')
		->where('sports_gradation_certificates.district_sportsperson_belongs', $district)
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

        return view('dso.certificate_grad_list', compact('sportsCertificates'));

    }
	
	 public function viewAppliedCertificate(Request $request)
{
	$district = session('district');
    // Validate POST input
    $request->validate([
        'certificate_id' => 'required|integer|exists:sports_gradation_certificates,id',
    ]);

    $id = $request->certificate_id;

    $otpData = sports_gradation_certificate::join('category_wise_gradations', 'sports_gradation_certificates.tournament_name', '=', 'category_wise_gradations.id')
        ->where('sports_gradation_certificates.id', $id)
		->where('sports_gradation_certificates.district_sportsperson_belongs', $district)
        ->whereIn('category_wise_gradations.gradation', ['C', 'D'])
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

    return view('dso.viewAppliedCertificate', compact('otpData'));
}

	
    public function create() {
        
		$district = session('district');
        return view('sports_kit.requisition', compact('district'));
   }

   // Store the requisition request
   public function store(Request $request) {

   $district = session('district');
       $request->validate([
           //'district' => 'required|string|max:100',
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
           'district' => $district,
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
	   $district = session('district');
		$request = SportsKitRequisition::where('id', $id)
        ->where('district', $district) 
        ->first();

		if (!$request) {
			return redirect()->back()->with('error', 'Request not found!');
		}

		$request->verification_status = 'Verified';
		$request->status = 'Verified'; // Update status
		$request->verification_datetime = \Carbon\Carbon::now('Asia/Kolkata');
		$request->save();
		
		$result = DB::table('user_details')
		->join('users', 'user_details.user_id', '=', 'users.id')
		->where('user_details.district', $request->district)
		->where('user_details.block_town', $request->block)
		->where('user_details.ward_village', $request->area_name)
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

		$encryp_password = sha1(trim($password));
		$msgidi = $this->sendSingleSMS($username, $encryp_password, $senderid, $msg, $mobile, $dept_key, $temp_id);

       return redirect()->back()->with('success', 'Request verified successfully!');
   }


   public function notVerifyRequest(Request $req,$id)
   {
    $district = session('district');

    $request = SportsKitRequisition::where('id', $id)
        ->where('district', $district) 
        ->first();
    if (!$request) {
        return redirect()->back()->with('error', 'Request not found!');
    }

       $request->verification_status = 'Not Verified';
       $request->status = 'Not Verified';
	   $request->not_verify_remarks = $req->not_verify_remark;
       $request->verification_datetime =  \Carbon\Carbon::now('Asia/Kolkata');
       $request->save();
	   
	   $result = DB::table('user_details')
		->join('users', 'user_details.user_id', '=', 'users.id')
		->where('user_details.district', $request->district)
		->where('user_details.block_town', $request->block)
		->where('user_details.ward_village', $request->area_name)
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

		$encryp_password = sha1(trim($password));
		$msgidi = $this->sendSingleSMS($username, $encryp_password, $senderid, $msg, $mobile, $dept_key, $temp_id);

       return redirect()->back()->with('error', 'Request marked as not verified!');
   }

   public function ApproveRequest($id)
   {
    $district = session('district');

    $request = sports_gradation_certificate::where('id', $id)
        ->where('district_sportsperson_belongs', $district) 
        ->first();

    if (!$request) {
        return redirect()->back()->with('error', 'Request not found!');
    }

    $request->status = 'Approved';
    //$request->status = 'Verified'; // Update status
    $request->approve_reject_datetime = \Carbon\Carbon::now('Asia/Kolkata');
    $request->save();
	
	$result = DB::table('user_details')
		->join('users', 'user_details.user_id', '=', 'users.id')
		->where('user_details.district', $request->district)
		->where('user_details.block_town', $request->block)
		->where('user_details.ward_village', $request->area_name)
		->select('user_details.user_id', 'users.mobile')
		->first();
	
		// SMS configuration
		$username = config('sms.username');
		$password = config('sms.password');
		$senderid = config('sms.senderid');
		$dept_key = config('sms.dept_key');
		$temp_id  = config('sms.temp_id2');

		$status= $request->status;
		$scheme= 'Haryana Sports Gradation';
		$app_id= $request->appl_id;
		$mobile= $request->mobile_no;
		$msg = "Dear User, your status for application $app_id for $scheme is $status. Sports Department, Haryana";

		$encryp_password = sha1(trim($password));
		$msgidi = $this->sendSingleSMS($username, $encryp_password, $senderid, $msg, $mobile, $dept_key, $temp_id);

       return redirect()->back()->with('success', 'Request Approved successfully!');
   }

   public function RejectRequest(Request $req,$id)
   {
		$district = session('district');

		$certificate = sports_gradation_certificate::where('id', $id)
			->where('district_sportsperson_belongs', $district)
			->first();

		if (!$certificate) {
			return redirect()->back()->with('error', 'Request not found!');
		}

		$certificate->status = 'Rejected';
		$certificate->rejection_remarks = $req->rejection_remark;
		$certificate->approve_reject_datetime = \Carbon\Carbon::now('Asia/Kolkata');
		$certificate->save();
		
		
	
		// SMS configuration
		$username = config('sms.username');
		$password = config('sms.password');
		$senderid = config('sms.senderid');
		$dept_key = config('sms.dept_key');
		$temp_id  = config('sms.temp_id2');

		$status= $certificate->status;
		$scheme= 'Haryana Sports Gradation';
		$app_id= $certificate->appl_id;
		$mobile= $certificate->mobile_no;
		$msg = "Dear User, your status for application $app_id for $scheme is $status. Sports Department, Haryana";

		$encryp_password = sha1(trim($password));
		$msgidi = $this->sendSingleSMS($username, $encryp_password, $senderid, $msg, $mobile, $dept_key, $temp_id);

       return redirect()->back()->with('success', 'Request Rejected successfully!');
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
		$certificate->certificate_pdf = $filePath;
		$certificate->save();

		return back()->with('success', 'Certificate uploaded successfully.');
	}
	
	public function UploadLetter(Request $request)
	{
		$request->validate([
			'enquiry_pdf' => 'required|mimes:pdf|max:2048',
			'certificate_id' => 'required|exists:sports_gradation_certificates,id'
		]);

		try {
			// Upload file
			$file = $request->file('enquiry_pdf');
			$filePath = $file->store('uploads/enquiries', 'public');

			// Save to DB
			$certificate = sports_gradation_certificate::find($request->certificate_id);
			$certificate->enquiry_pdf = $filePath;
			$certificate->enquiry_pdf_datetime = now('Asia/Kolkata');
			$certificate->save();

			return response()->json([
				'success' => true,
				'file_url' => asset('storage/' . $filePath),
				'upload_date' => \Carbon\Carbon::parse($certificate->enquiry_pdf_datetime)->format('d M Y')
			]);
		} catch (\Exception $e) {
			\Log::error('Upload error: ' . $e->getMessage());
			return response()->json(['success' => false, 'message' => 'Upload failed.']);
		}
	}

	
	public function RepliedLetter(Request $request)
{
    $request->validate([
        'replied_pdf' => 'required|mimes:pdf|max:2048',
        'certificate_id' => 'required|exists:sports_gradation_certificates,id'
    ]);

    try {
        $file = $request->file('replied_pdf');
        $filePath = $file->store('uploads/enquiries', 'public');

        $certificate = sports_gradation_certificate::find($request->certificate_id);
        $certificate->replied_pdf = $filePath;
        $certificate->replied_pdf_datetime = now('Asia/Kolkata');
        $certificate->save();

        return response()->json([
            'success' => true,
            'file_url' => asset('storage/' . $filePath),
            'upload_date' => \Carbon\Carbon::parse($certificate->replied_pdf_datetime)->format('d M Y')
        ]);
    } catch (\Exception $e) {
        \Log::error('Reply upload error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Upload failed. Please try again.'
        ]);
    }
}


	
	
	public function storeKitDisbursement(Request $request)
{
    // Step 1: Validate vendor-related fields
    $vendorData = $request->validate([
        'firm_name'      => 'required|string|max:255',
        'owner_name'     => 'required|string|max:255',
        'mobile_number'  => 'required|string|size:10',
    ]);

    // Step 2: Create vendor
    $vendor = Vendor::create([
        'vendor_name' => $vendorData['firm_name'],
        'owner_name'  => $vendorData['owner_name'],
        'mob'         => $vendorData['mobile_number']
    ]);

    // Step 3: Validate disbursement-related fields
    $disbursementData = $request->validate([
        'request_id'          => 'required|exists:sports_kit_requisitions,id',
        'fund_source'         => 'required|in:DSE,HQ',
        'procurement_amount'  => 'required|numeric',
        'bill_no'             => 'required|string',
        'voucher_file'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    // Step 4: Handle voucher file upload
    $voucherPath = null;
    if ($request->hasFile('voucher_file')) {
        $voucherFile = $request->file('voucher_file');
        $voucherPath = $voucherFile->storeAs(
            'uploads/vouchers',
            uniqid() . '_' . $voucherFile->getClientOriginalName(),
            'public'
        );
    }

	EquipmentVendorAssignment::create([
		 'request_id' => $disbursementData['request_id'],
		 'vendor_id' => $vendor->id
	 ]);
						 
    // Step 5: Find assignment and update
    $assignment = EquipmentVendorAssignment::where('request_id', $disbursementData['request_id'])
        ->where('vendor_id', $vendor->id)
        ->firstOrFail();

    $assignment->update([
        'fund_source'         => $disbursementData['fund_source'],
        'procurement_amount'  => $disbursementData['procurement_amount'],
        'bill_no'             => $disbursementData['bill_no'],
        'voucher_file_path'   => $voucherPath,
    ]);

    // Step 6: Update disbursement status
    $this->updateDisbursementStatus($disbursementData['request_id'], $vendor->id);

    return back()->with('success', 'Kit disbursement done successfully.');
}



protected function updateDisbursementStatus($requestId, $vendorId)
{
    $assignments = EquipmentVendorAssignment::where('request_id', $requestId)
        ->where('vendor_id', $vendorId)
        ->get();

    $fullyDisbursed = $assignments->every(function ($assignment) {
        return !is_null($assignment->procurement_amount) && !is_null($assignment->voucher_file_path);
    });

    if ($fullyDisbursed) {
        SportsKitRequisition::where('id', $requestId)->update(['disbursement_status' => 'Completed']);
    }
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
