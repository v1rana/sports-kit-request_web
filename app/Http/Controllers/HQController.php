<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HQ;
use App\Models\HQSportsRequest;
use App\Models\SportsKitRequisition;
use App\Models\Vendor;

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
      $totalApplications = SportsKitRequisition::count();

      // Fetch total approved applications
      $totalApproved = SportsKitRequisition::where('status', 'Approved')->count();

      // Fetch total rejected applications
      $totalRejected = SportsKitRequisition::where('status', 'Rejected')->count();
      $totalPending = SportsKitRequisition::where('status', 'Pending')->count();
      $totalVerified = SportsKitRequisition::where('status', 'Verified')->count();
      $totalNotVerified = SportsKitRequisition::where('status', 'Not Verified')->count();
      $totalDisbursed = SportsKitRequisition::where('status', 'Disbursed')->count();

        return view('hq.dashboard', compact('totalApplications', 'totalApproved', 'totalRejected', 'totalPending', 'totalVerified', 'totalNotVerified', 'totalDisbursed'));
    }

    // Assign HQ to a sports requisition request
    public function assignVendor(Request $request)
{
    // Validate request
    $request->validate([
        'request_id' => 'required|exists:sports_kit_requisitions,id',
        'vendor_id' => 'required|exists:vendors,id',
    ]);

    // Find the requisition request
    $requisition = SportsKitRequisition::find($request->request_id);

    if (!$requisition) {
        return redirect()->back()->with('error', 'Request not found!');
    }

    // Assign vendor & update status
    $requisition->vendor_id = $request->vendor_id;
    $requisition->vendor_assign_date = now();
    $requisition->save();

    return redirect()->back()->with('success', 'Vendor assigned successfully!');
}
}
