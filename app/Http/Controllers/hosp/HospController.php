<?php

namespace App\Http\Controllers\hosp;

use App\Http\Controllers\Controller;
use App\Models\Declaration;
use App\Models\DeclarationsHosp;
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
            'age' => 'required',
            'photo' => 'required',
            'domicile' => 'required',
            'dob_doc' => 'required',
            'played_national_level' => 'required|in:1,2',
            'national_level_doc' => 'required_if:played_national_level,1',
            'organisation_represented' => 'required_if:played_national_level,2',
            'organisation_doc' => 'required_if:played_national_level,2',

        ]);
        try {
            $id = $request->user()->id;
            $user = User::where('id', $id)->findOrFail($id);
           $user_details = UserDetails::where('user_id', $id)->firstOrFail();
            $user->mobile = $request->mobile;
            $user->email = $request->email_id;
            $user->save();

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('photo', 'public');
                $user_details->photo = basename($path);
            }
            if ($request->hasFile('dob_doc')) {
                $path = $request->file('dob_doc')->store('certificates', 'public');
                $user_details->dob_doc = basename($path);
            }
            if ($request->hasFile('domicile_doc')) {
                $path = $request->file('domicile_doc')->store('certificates', 'public');
                $user_details->domicile_doc = basename($path);
            }
            if ($request->hasFile('national_level_doc')) {
                // Validate file size if the file is uploaded
                $validationResponse = $this->validateFileSize(
                    $request->file('national_level_doc'),
                    500, // Max size in KB
                    'The national level document must not exceed 500KB.'
                );
                $path = $request->file('national_level_doc')->store('certificates', 'public');
                $user_details->national_level_doc = basename($path);
                $user_details->organisation_doc =NULL;
                $request['organisation_represented'] =NULL;
            }
            if ($request->hasFile('organisation_doc')) {
                $path = $request->file('organisation_doc')->store('certificates', 'public');
                $user_details->organisation_doc = basename($path);
                $user_details->national_level_doc = NULL;
            }
            
            $user_details->domicile = $request->domicile;
            $user_details->aadhaar = $request->aadhaar;
            $user_details->age = $request->age;
            $user_details->played_national_level = $request->played_national_level;
            $user_details->organisation_represented = $request->organisation_represented;
            $user_details->save();
            $user->load('userDetails', 'sportsDisciplineHosp', 'educationHosp', 'declarationsHosp');
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
            // 'tournament' => 'required|exists:schedule_1_2,id',

            // 'domicile' => 'required|in:1,2',
            // 'domicile_certificate' => [
            //     Rule::requiredIf($request->domicile == 1),
            //     'nullable',
            //     'file',
            //     'mimes:pdf,jpg,jpeg,png',
            // ],

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
            // 'domicile_certificate.required_if' => 'The domicile certificate is required when domicile is Yes.',
            'national_certificate.required_if' => 'The national certificate is required when played national is Yes.',
            'org_certificate.required_if' => 'The organisation certificate is required when played national is No.',
        ]);


        $user = $request->user();

        $event = EventHosp::updateOrCreate(
            ['user_id' => $request->user()->id], // condition
            [
                'event_type' => $request->event_type,
                // 'aadhaar' => $request->aadhaar,
                // 'tournament_id' => $request->tournament,
                // 'domicile' => $request->domicile,
                'played_national_level' => $request->played_national,
                'organisation_represented' => $request->played_national == 2 ? $request->central_org_name : null,
            ]
        );

        // File uploads (optional)
        // if ($request->hasFile('domicile_certificate')) {
        //     $event->domicile_doc = $request->file('domicile_certificate')->store('certificates');
        // }
        if ($request->hasFile('national_certificate')) {
            $event->national_level_doc = $request->file('national_certificate')->store('certificates', 'public');
        }
        if ($request->played_national == 2 && $request->hasFile('org_certificate')) {
            $event->organisation_doc = $request->file('org_certificate')->store('certificates', 'public');
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
            // 'tournament' => 'required|exists:schedule_1_2,id',

            // 'domicile' => 'required|in:1,2',
            // 'domicile_certificate' => 'required_if:domicile,1',
            // [
            //     'domicile_certificate.required_if' => 'The domicile certificate field is required when domicile is Yes.',
            // ],

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
        // $event->tournament_id = $request->tournament;
        // $event->domicile = $request->domicile;
        $event->played_national_level = $request->played_national;
        $event->organisation_represented = $request->played_national == 2 ? $request->central_org_name : null;

        // Handle optional file uploads
        // if ($request->hasFile('domicile_certificate')) {
        //     $event->domicile_doc = $request->file('domicile_certificate')->store('certificates');
        // }

        if ($request->hasFile('national_certificate')) {
            $event->national_level_doc = $request->file('national_certificate')->store('certificates', 'public');
        }

        if ($request->played_national == 2 && $request->hasFile('org_certificate')) {
            $event->organisation_doc = $request->file('org_certificate')->store('certificates', 'public');
        }

        $event->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Event updated successfully',
            'data' => $event,
        ]);
    }
    public function getUserData(Request $request)
    {
        $user = $request->user(); // Assuming Sanctum auth

        $user = User::where('id', $user->id)->first();

        $user->load('userDetails', 'sportsDisciplineHosp', 'educationHosp', 'declarationsHosp');
        return response()->json([
            'status' => 'success',
            'message' => 'User details',
            'user' => collect($user)->map(function ($value) {
                return $value === null ? '' : $value;
            }),
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
            'educations.*.id' => 'nullable|integer' // for update tracking
        ]);

        $userId = $request->user()->id;
        $incomingIds = [];

        foreach ($request->educations as $item) {
            $education = isset($item['id'])
                ? EducationHOSP::where('id', $item['id'])->where('user_id', $userId)->first()
                : new EducationHOSP();

            if (!$education) {
                $education = new EducationHOSP();
                $education->user_id = $userId;
            }
            $education->user_id = $userId;
            $education->qualification = $item['qualification'];
            $education->other_qualification = $item['otherText'] ?? null;

            // Only replace file if new one is uploaded
            if (isset($item['certificate']) && $item['certificate'] instanceof \Illuminate\Http\UploadedFile) {
                $path = $item['certificate']->store('education-certificates', 'public');
                $education->certificate_path = basename($path);
            }

            $education->save();
            $incomingIds[] = $education->id;
        }

        // Optional: remove deleted items
        EducationHOSP::where('user_id', $userId)
            ->whereNotIn('id', $incomingIds)
            ->delete();

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
            'event_type' => 'required',
            'disability_type_id' => 'required_if:physical_disability,1|exists:disability_types,id|nullable',
            'disability_doc' => 'required_if:physical_disability,1|nullable',

            'tournament_id' => 'required|exists:schedule_1_2,id',
            'game_id' => 'required|exists:games,id',
            'organizing_committee' => 'required|string',

            'tournament_level' => 'required|in:1,2', // 1=National, 2=International
            'represented_india' => 'nullable|required_if:tournament_level,1|in:0,1,2',

            'achievement_date' => 'required|date',
            'tournament_venue' => 'required|string',
            // 'medal_won' => 'required|string',
            // 'match_played_by_me' => 'required|string',
            // 'participation_level' => 'required|string',
            'osp_achivement_certificate_path' => 'required|nullable',
            // 'international_achievement_Verification_certificate_path' => 'required|nullable',
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
            'event_type' => $request->event_type,
            'organizing_committee' => $request->organizing_committee,
            'tournament_level' => $request->tournament_level,
            'represented_india' => $request->represented_india ?? 0,
            'achievement_date' => $request->achievement_date,
            'tournament_venue' => $request->tournament_venue,
            'medal_won' => $request->medal_won,
            'match_played_by_team' => $request->match_played_by_team,
            'match_played_by_me' => $request->match_played_by_me,
        ];

        // Add certificate_path only if it's not null
        // if ($pathCertificate !== null) {
        //     $updateData['certificate_path'] = $pathCertificate;
        // }
        if ($request->hasFile('disability_doc')) {
            $pathDisability = $request->hasFile('disability_doc')
                ? $request->file('disability_doc')->store('certificates', 'public')
                : null;
            $updateData['disability_doc'] = basename($pathDisability);
        }
        if ($request->hasFile('osp_achivement_certificate_path')) {
            $pathCertificate = $request->file('osp_achivement_certificate_path')->store('certificates', 'public');
            $updateData['osp_achivement_certificate_path'] = basename($pathCertificate);
        }
        if ($request->hasFile('international_achievement_Verification_certificate_path')) {
            $pathCertificate = $request->file('international_achievement_Verification_certificate_path')->store('certificates', 'public');
            $updateData['international_achievement_Verification_certificate_path'] = basename($pathCertificate);
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

    public function storeDeclarations(Request $request)
    {
        $request->validate([
            'declaration_ids' => 'required|array|min:1',
            'declaration_ids.*' => 'exists:declarations,id',
            // 'declaration_file' => 'file|mimes:pdf|max:2048',
            'declaration_file' => 'required',
        ]);

        $userId = $request->user()->id; // Or get from request if not using auth

        // Save the file if present
        $filePath =  $request->declaration_file;
        if ($request->hasFile('declaration_file')) {
            // $filePath = $request->file('declaration_file')->store('declarations', 'public');
            $filePath = $request->file('declaration_file')->store('declarations', 'public');
            $filePath = basename($filePath);
        }

        // Optional: Clear old records if needed
        DeclarationsHosp::where('user_id', $userId)->delete();

        // Save declarations, attach file only once (e.g. to the first)
        foreach ($request->declaration_ids as $index => $declarationId) {
            DeclarationsHosp::create([
                'user_id' => $userId,
                'declaration_id' => $declarationId,
                // 'declaration_file' =>  $index == 0 ? ($filePath ?? '') : null,
                'declaration_file' =>   $filePath ?? '',
            ]);
        }

        return response()->json(['message' => 'Declarations saved successfully.']);
    }

    public function getHospDeclarations(Request $request)
    {
        $userId = $request->user()->id;

        $declarations = DeclarationsHosp::where('user_id', $userId)->with('declaration')->get();

        return response()->json([
            'declaration_ids' => $declarations,
        ]);
    }

    public function getDeclarations()
    {
        $declarations = Declaration::get();

        return response()->json([
            'declarations' => $declarations,
        ]);
    }

    private function validateFileSize($file, $maxSizeInKB, $errorMessage)
    {
        // Convert max size to bytes
        $maxFileSize = $maxSizeInKB * 1024; // In bytes

        // Check if the file size exceeds the limit
        if ($file->getSize() > $maxFileSize) {
            // Return error response if the file size exceeds the limit
            return response()->json([
                'status' => 'error',
                'message' => $errorMessage
            ], 422);
        }

        // Return null if no errors
        return null;
    }
}
