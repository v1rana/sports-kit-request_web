<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SportsKitRequisition;

class ADCController extends Controller
{
    public function index()
    {
        // Fetch sports requests with HQ verification status
        $sportsRequests = SportsKitRequisition::with('hqSportsRequest')
        ->where('verification_status', 'Verified') // Only fetch verified requests
        ->get();

    return view('adc.sports_requests_list', compact('sportsRequests'));
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

    return redirect()->back()->with('success', 'Request approved successfully!');
}

public function rejectRequest($id)
{
    $request = SportsKitRequisition::find($id);

    if (!$request) {
        return redirect()->back()->with('error', 'Request not found!');
    }

    $request->approval_status = 'Rejected';
    $request->status = 'Rejected';
    $request->approval_rejection_datetime = now();
    $request->save();

    return redirect()->back()->with('error', 'Request rejected successfully!');
}

}
