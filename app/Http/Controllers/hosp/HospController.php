<?php

namespace App\Http\Controllers\hosp;

use App\Http\Controllers\Controller;
use App\Models\EventHosp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HospController extends Controller
{
    public function store(Request $request)
    {


        $request->validate([
            'event_type' => 'required|string',
            'aadhaar' => 'required|digits:12',
            'tournament' => 'required|exists:schedule_1_2,id',

            'domicile' => 'required|in:1,2',
            'domicile_certificate' => [
                Rule::requiredIf($request->domicile == 1),
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
            ],

            'played_national' => 'required|in:1,2',
            'national_certificate' => [
                Rule::requiredIf($request->played_national == 1),
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
            ],

            'central_org_name' => [
                Rule::requiredIf($request->played_national == 2),
                'string',
            ],
            'org_certificate' => [
                Rule::requiredIf($request->played_national == 2),
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
            ],
        ], [
            'domicile_certificate.required_if' => 'The domicile certificate is required when domicile is Yes.',
            'national_certificate.required_if' => 'The national certificate is required when played national is Yes.',
            'org_certificate.required_if' => 'The organisation certificate is required when played national is No.',
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

    public function update(Request $request, $id)
    {
        $event = EventHosp::where('user_id', $request->user()->id)->findOrFail($id);

        $request->validate([
            'event_type' => 'required|string',
            'aadhaar' => 'required|digits:12',
            'tournament' => 'required|exists:schedule_1_2,id',

            'domicile' => 'required|in:1,2',
            'domicile_certificate' => 'required_if:domicile,1',
            [
                'domicile_certificate.required_if' => 'The domicile certificate field is required when domicile is Yes.',
            ],

            'played_national' => 'required|in:1,2',
            'national_certificate' => 'required_if:played_national,1',

            'central_org_name' => [
                Rule::requiredIf(fn() => $request->played_national == 2),
                'string',
                'nullable',
            ],
            'org_certificate' => 'required_if:played_national,2',
        ]);

        $event->event_type = $request->event_type;
        $event->aadhaar = $request->aadhaar;
        $event->tournament_id = $request->tournament;
        $event->domicile = $request->domicile;
        $event->played_national_level = $request->played_national;
        $event->organisation_represented = $request->played_national == 2 ? $request->central_org_name : null;

        // Handle optional file uploads
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
            'message' => 'Event updated successfully',
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


    

    public function download($filename)
    {
        $path = storage_path('app/private/certificates/' . $filename);

        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->file($path);
    }
}
