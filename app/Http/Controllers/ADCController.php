<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SportsKitRequisition;
use App\Models\Vendor;
use App\Models\Sport;
use App\Models\EquipmentVendorAssignment;
use Illuminate\Support\Facades\DB;

class ADCController extends Controller
{
    public function index()
    {
        // Fetch sports requests with HQ verification status
        $sportsRequests = SportsKitRequisition::with('hqSportsRequest')
        ->whereNotIn('status', ['Not Verified','Pending']) // Only fetch verified requests
        ->get();

    return view('adc.sports_requests_list', compact('sportsRequests'));
    }

    public function dashboard() {
        // Fetch total application count
        $totalApplications = SportsKitRequisition::whereIn('status', ['Approved', 'Rejected', 'Disbursed', 'Verified'])->count();


        // Fetch total approved applications
        $totalApproved = SportsKitRequisition::where('status', 'Approved')->count();

        // Fetch total rejected applications
        $totalRejected = SportsKitRequisition::where('status', 'Rejected')->count();
        $totalDisbursed = SportsKitRequisition::where('status', 'Disbursed')->count();
        $totalVerified = SportsKitRequisition::where('status', 'Verified')->count();
        return view('adc.dashboard', compact('totalApplications', 'totalApproved', 'totalRejected', 'totalDisbursed','totalVerified'));
    }


public function approveRequest($id)
{
    $request = SportsKitRequisition::find($id);

    if (!$request) {
        return redirect()->back()->with('error', 'Request not found!');
    }

    $request->approval_status = 'Approved';
    $request->status = 'Approved';
    $request->approval_rejection_datetime = now();
    $request->save();
	
	 // ✅ Auto-assign vendor per sport
    $sportsEquipment = json_decode($request->sports_equipment, true);

    // Get unique sport names
    $sports = collect($sportsEquipment)->pluck('name')->unique();
	
	$sportIdMap = Sport::whereIn('sports_name', $sports)
    ->pluck('id', 'sports_name');

   foreach ($sports as $sportName) {
		$sportId = $sportIdMap[$sportName] ?? null;

		if ($sportId) {
			// Step 3: Get vendor for this sport
			$vendorMapping = DB::table('sport_vendor')
				->where('sport_id', $sportId)
				->first();

			if ($vendorMapping) {
				// Step 4: Assign vendor
				EquipmentVendorAssignment::updateOrCreate(
					[
						'request_id' => $request->id,
						'equipment_name' => $sportName,
					],
					[
						'vendor_id' => $vendorMapping->vendor_id,
					]
				);
			}
		}
	}
	
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

		$encryp_password = sha1(trim($password));
		$msgidi = $this->sendSingleSMS($username, $encryp_password, $senderid, $msg, $mobile, $dept_key, $temp_id);

    return redirect()->back()->with('success', 'Request approved successfully!');
}

public function rejectRequest(Request $req,$id)
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
