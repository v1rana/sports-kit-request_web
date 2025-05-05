<?php

namespace App\Http\Controllers\hosp;

use App\Http\Controllers\Controller;
use App\Models\EducationHOSP;
use App\Models\EventHosp;
use App\Models\GameHosp;
use App\Models\Schedule12;
use App\Models\SportsDisciplineHosp;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HospController extends Controller
{

    public function updateUserDetails(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'aadhaar' => 'required|digits:12',
            'email_id' => 'required|email',
            'photo' => 'required',
        ]);
        try {
            $id = $request->user()->id;
            $user = User::where('id', $id)->findOrFail($id);
            $user_details = UserDetails::where('user_id', $id)->findOrFail($id);

            $user->mobile = $request->mobile;
            $user->email = $request->email_id;
            $user->save();

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('photo');
                $user_details->photo = basename($path);
            }
            $user_details->aadhaar = $request->aadhaar;
            $user_details->save();
            $user->load('userDetails', 'eventHosp', 'sportsDisciplineHosp', 'declarationsHosp');
            return response()->json([
                'status' => 'success',
                'message' => 'User details saved successfully',
                'user' => $user,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function store(Request $request)
    {


        $request->validate([
            'event_type' => 'required|string',
            // 'aadhaar' => 'required|digits:12',
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
                // 'aadhaar' => $request->aadhaar,
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
            // 'aadhaar' => 'required|digits:12',
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
        // $event->aadhaar = $request->aadhaar;
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
    public function getEducationData(Request $request)
    {
        $user = $request->user(); // Assuming Sanctum auth

        $event = EducationHOSP::where('user_id', $user->id)->get();

        if (!$event) {
            return response()->json(['message' => 'No event data found'], 404);
        }

        return response()->json($event);
    }




    public function download($filename, $foldername)
    {
        $path = storage_path("app/private/$foldername/$filename");

        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->file($path);
    }

    public function storeOrUpdateEducation(Request $request)
    {
        $request->validate([
            'educations' => 'required|array',
            'educations.*.qualification' => 'required|string',
            'educations.*.otherText' => 'nullable|string',
            'educations.*.certificate' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $userId = $request->user()->id;

        // Optional: Clear previous entries if you're replacing all
        EducationHOSP::where('user_id', $userId)->delete();

        foreach ($request->educations as $index => $item) {
            $education = new EducationHOSP();
            $education->user_id = $userId;
            $education->qualification = $item['qualification'];
            $education->other_qualification = $item['otherText'] ?? null;

            if (isset($item['certificate']) && $item['certificate'] instanceof \Illuminate\Http\UploadedFile) {
                $path = $item['certificate']->store('education-certificates');
                $education->certificate_path = basename($path);
            }

            $education->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Education data saved successfully.',
        ]);
    }

    public function getSchedule($event_type)
    {
        $schedule_list = Schedule12::where('event_type', $event_type)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'listing',
            'list' => $schedule_list,
        ]);
    }

    public function getGames()
    {
        $list = GameHosp::get();
        return response()->json([
            'status' => 'success',
            'message' => 'listing',
            'list' => $list,
        ]);
    }

    public function storeSportDiscipline(Request $request)
    {
        $validated = $request->validate([
            'physical_disability' => 'required|in:1,2',
            'disability_type_id' => 'required_if:physical_disability,1|exists:disability_types,id|nullable',
            'disability_doc' => 'required_if:physical_disability,1|nullable',

            'tournament_id' => 'required|exists:schedule_1_2,id',
            'game_id' => 'required|exists:games,id',
            'organizing_committee' => 'required|string',
            'tournament_level' => 'required|in:1,2', // 1=National, 2=International
            'represented_india' => 'required_if:tournament_level,1|in:1,2',

            'achievement_date' => 'required|date',
            'tournament_venue' => 'required|string',
            'medal_won' => 'required|string',
            'participation_level' => 'required|string',
            'certificate_path' => 'required|nullable',
        ]);
        // As above

        $user =  $request->user();
        // $user =  $request->user()->id;
        
        // Base update data
        $updateData = [
            'physical_disability' => $request->physical_disability,
            'disability_type_id' => $request->disability_type_id,
            'tournament_id' => $request->tournament_id,
            'game_id' => $request->game_id,
            'organizing_committee' => $request->organizing_committee,
            'tournament_level' => $request->tournament_level,
            'represented_india' => $request->represented_india ?? 0,
            'achievement_date' => $request->achievement_date,
            'tournament_venue' => $request->tournament_venue,
            'medal_won' => $request->medal_won,
            'participation_level' => $request->participation_level,
        ];

        // Add certificate_path only if it's not null
        // if ($pathCertificate !== null) {
        //     $updateData['certificate_path'] = $pathCertificate;
        // }
        if ($request->hasFile('disability_doc')) {
            $pathDisability = $request->hasFile('disability_doc')
                ? $request->file('disability_doc')->store('certificates')
                : null;
                $updateData['disability_doc'] = basename($pathDisability);
        }
        if ($request->hasFile('certificate_path')) {
            $pathCertificate = $request->file('certificate_path')->store('certificates');
            $updateData['certificate_path'] = basename($pathCertificate);
        }
        SportsDisciplineHosp::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            $updateData
        );


        return response()->json(['message' => 'Saved successfully'], 201);
    }
    public function getSportDiscipline(Request $request)
    {
        $user = $request->user();
        $data = $user->sportsDisciplineHosp()->latest()->first();

        if (!$data) {
            return response()->json(['message' => 'No event data found'], 404);
        }

        return response()->json($data);
    }
}
