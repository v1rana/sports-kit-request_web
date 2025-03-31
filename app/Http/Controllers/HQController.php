<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HQ;
use App\Models\HQSportsRequest;
use App\Models\SportsKitRequisition;

class HQController extends Controller
{
    // Show all sports requisition requests
    public function index()
    {
        $sportsRequests = SportsKitRequisition::with('hqSportsRequest')
        ->where('verification_status', 'Verified') // Only fetch verified requests
        ->get();

        return view('hq.sports_requests_list', compact('sportsRequests'));
    }

    // Assign HQ to a sports requisition request
    public function assignHQ(Request $request)
    {
        $request->validate([
            'hq_id' => 'required|exists:hqs,id',
            'sports_kit_requisition_id' => 'required|exists:sports_kit_requisitions,id',
            'status' => 'required|in:approved,rejected',
        ]);

        HQSportsRequest::updateOrCreate(
            ['sports_kit_requisition_id' => $request->sports_kit_requisition_id],
            ['hq_id' => $request->hq_id, 'status' => $request->status]
        );

        return redirect()->back()->with('success', 'HQ assigned successfully!');
    }
}
