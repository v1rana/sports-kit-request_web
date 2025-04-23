<?php

namespace App\Http\Controllers\hosp;

use App\Http\Controllers\Controller;
use App\Models\EventHosp;
use Illuminate\Http\Request;

class HospController extends Controller
{
    public function store(Request $request)
    {
        // $request->validate([
        //     'event_type' => 'required|string',
        //     'tournament' => 'required|exists:schedule_1_2,id',
        //     'domicile' => 'required|in:1,2',
        //     'played_national' => 'required|in:1,2',
        //     'domicile_certificate' => 'nullable|file|mimes:pdf,jpg,png',
        //     'national_certificate' => 'nullable|file|mimes:pdf,jpg,png',
        //     'central_org_name' => 'nullable|string',
        //     'org_certificate' => 'nullable|file|mimes:pdf,jpg,png',
        // ]);

        $request->validate([
            'event_type' => 'required|string',
            'aadhaar' => 'required|digits:12',
            'tournament' => 'required|exists:schedule_1_2,id',

            'domicile' => 'required|in:1,2',
            'domicile_certificate' => 'required_if:domicile,1|file|mimes:pdf,jpg,jpeg,png',

            'played_national' => 'required|in:1,2',
            'national_certificate' => 'required_if:played_national,1|file|mimes:pdf,jpg,jpeg,png',

            'central_org_name' => 'required_if:played_national,2|string',
            'org_certificate' => 'required_if:played_national,2|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $user = $request->user();

        $event = EventHosp::updateOrCreate(
            ['user_id' => $request->user()->id], // condition
            [
                'event_type' => $request->event_type,
                'aadhaar' => $request->aadhaar,
                'tournament_id' => $request->tournament,
                'domicile' => $request->domicile,
                'played_national_level' => $request->played_national,
                'organisation_represented' => $request->played_national == 2 ? $request->central_org_name : null,
            ]
        );

        // File uploads (optional)
        if ($request->hasFile('domicile_certificate')) {
            $event->domicile_doc = $request->file('domicile_certificate')->store('certificates');
        }
        if ($request->hasFile('national_certificate')) {
            $event->national_level_doc = $request->file('national_certificate')->store('certificates');
        }
        if ($request->played_national == 2 && $request->hasFile('org_certificate')) {
            $event->organisation_doc = $request->file('org_certificate')->store('certificates');
        }

        $event->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Event details saved successfully',
            'data' => $event,
        ]);
    }

    public function getEventData(Request $request)
    {
        $user = $request->user(); // Assuming Sanctum auth

        $event = EventHosp::where('user_id', $user->id)->first();

        if (!$event) {
            return response()->json(['message' => 'No event data found'], 404);
        }

        return response()->json($event);
    }
}
