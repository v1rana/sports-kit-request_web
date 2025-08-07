<?php

namespace App\Http\Controllers\hosp;

use App\Http\Controllers\Controller;
use App\Models\Declaration;
use App\Models\DeclarationsHosp;
use App\Models\EducationHOSP;
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

    public function updateRole(Request $request)
    {
        $id = $request->user()->id;
        $user = User::where('id', $id)->findOrFail($id);
        $user->roles()->syncWithoutDetaching([$request->role_id]);
        return response()->json([
            'status' => 'success',
            'message' => 'Role upadated',
        ]);
    }
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
           $user_details = UserDetails::where([
                ['user_id', '=', $id],
                ['application_id', '=', $request->application_id],
                ['is_form_completed', '=', 0],
            ])->firstOrFail();
            $user->mobile = $request->mobile;
            $user->email = $request->email_id;
            
            $user->save();

            $err_msg = 'document must not exceed 500 KB.';
            if ($request->hasFile('photo')) {
                $validationResponse = $this->validateFileSize(
                    $request->file('photo'),
                    100, // Max size in KB
                    'Photo '. $err_msg
                );
                if (isset($validationResponse) && $validationResponse) {
                    return $validationResponse;
                } 
                $path = $request->file('photo')->store('photo', 'public');
                $user_details->photo = basename($path);
            }
            if ($request->hasFile('dob_doc')) {
                $validationResponse = $this->validateFileSize(
                    $request->file('dob_doc'),
                    500, // Max size in KB
                    'DOB '. $err_msg
                );
                if (isset($validationResponse) && $validationResponse) {
                    return $validationResponse;
                } 
                $path = $request->file('dob_doc')->store('certificates', 'public');
                $user_details->dob_doc = basename($path);
            }
            if ($request->hasFile('domicile_doc')) {
                $validationResponse = $this->validateFileSize(
                    $request->file('domicile_doc'),
                    500, // Max size in KB
                    'Domicile '. $err_msg
                );
                if (isset($validationResponse) && $validationResponse) {
                    return $validationResponse;
                } 
                $path = $request->file('domicile_doc')->store('certificates', 'public');
                $user_details->domicile_doc = basename($path);
            }
            if ($request->hasFile('national_level_doc')) {
                // Validate file size if the file is uploaded
                $validationResponse = $this->validateFileSize(
                    $request->file('national_level_doc'),
                    500, // Max size in KB
                    "The national level ". $err_msg
                );
                if (isset($validationResponse) && $validationResponse) {
                    return $validationResponse;
                } 
                $path = $request->file('national_level_doc')->store('certificates', 'public');
                $user_details->national_level_doc = basename($path);
                $user_details->organisation_doc =NULL;
                $request['organisation_represented'] =NULL;
            }
            if ($request->hasFile('organisation_doc')) {
                $validationResponse = $this->validateFileSize(
                    $request->file('organisation_doc'),
                    500, // Max size in KB
                    "Organisation doc ". $err_msg
                );
                if (isset($validationResponse) && $validationResponse) {
                    return $validationResponse;
                } 
                $path = $request->file('organisation_doc')->store('certificates', 'public');
                $user_details->organisation_doc = basename($path);
                $user_details->national_level_doc = NULL;
            }

           
            
            $user_details->domicile = $request->domicile;
            $user_details->aadhaar = $request->aadhaar;
            $user_details->age = $request->age;
            $user_details->played_national_level = $request->played_national_level;
            $user_details->organisation_represented = $request->organisation_represented;
            $user_details->active_step = '2';
            $user_details->save();
            $user->load(['roles',
            'applicationDetails' => function ($query) use ($request) {
                $query->where('application_id', $request->application_id);
            },
            'sportsDisciplineHosp', 'sportsDisciplineHosp.tournament',
            'sportsDisciplineHosp.game',
            'sportsDisciplineHosp.disabilityType' , 'educationHosp', 'declarationsHosp']);
            return response()->json([
                'status' => 'success',
                'message' => 'User details saved successfully',
                'user' => $user
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
   
    public function getUserData(Request $request)
    {
        $user = $request->user(); // Assuming Sanctum auth

        $user = User::where('id', $user->id)->first();

        $user->load('roles','userDetails', 'sportsDisciplineHosp', 'sportsDisciplineHosp.tournament',
        'sportsDisciplineHosp.game',
        'sportsDisciplineHosp.disablilityType' , 'educationHosp', 'declarationsHosp');
        return response()->json([
            'status' => 'success',
            'message' => 'User details',
            'user' => collect($user)->map(function ($value) {
                return $value === null ? '' : $value;
            }),
        ]);
    }

    public function getUserApplications(Request $request)
    {
        $user = $request->user(); // Assuming Sanctum auth

        $user = User::where('id', $user->id)->select([
                     
            'id','status'])->first();

        $user->load(['roles:id,name',
        'userDetails' => function ($query) {
            $query->where('is_form_completed', 1)
                  ->select([
                     
                      'user_id',
                      'application_id',
                      'full_name_en',
                      'date_of_birth',
                      'played_national_level',
                      'domicile',
                      'caste_category',
                      // add other desired columns
                  ])
                  ->with(['sportsDisciplineHosp' => function ($q) {
                      $q->select([
                          'id',
                          'application_id',
                          'medal_won',
                          'disability_type_id',
                          'tournament_id',
                          'game_id',
                          // add other desired columns
                      ])
                      ->with([
                        'tournament' => function ($t) {
                            $t->select([
                                'id',
                                'tournament', // or whatever columns you want
                                // Add more tournament fields as needed
                            ]);
                        },
                        'game' => function ($g) {
                            $g->select([
                                'id',
                                'name', // replace with actual column names
                            ]);
                        },
                        'disabilityType' => function ($d) {
                            $d->select([
                                'id',
                                'type', // replace with actual column names
                            ]);
                        }
                    ]);
                  }]);
                },
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'User details',
            'user' => collect($user)->map(function ($value) {
                return $value === null ? '' : $value;
            }),
        ]);
    }

    public function getCompleteApplication(Request $request,$application_id)
    {
        $user = $request->user(); // Assuming Sanctum auth

        $user = User::where('id', $user->id)->select([
                     
            'id','status','mobile'])->first();

        $user->load(['roles:id,name',
        'applicationDetails' => function ($query) use ($application_id) {
            $query->where('is_form_completed', 1)->where('application_id',$application_id)
                  ->select([
                     
                      'user_id',
                      'application_id',
                      'full_name_en',
                      'date_of_birth',
                      'played_national_level',
                      'domicile',
                      'caste_category',
                      'aadhaar',
                      'photo',
                      'organisation_represented',
                      'domicile_doc',
                      'national_level_doc'
                      // add other desired columns
                  ])
                  ->with(['sportsDisciplineHosp' => function ($q) {
                      $q->select([
                          'id',
                          'application_id',
                          'medal_won',
                          'disability_type_id',
                          'tournament_id',
                          'game_id',
                          'tournament_venue',
                          'achievement_date',
                          'match_played_by_team',
                          'match_played_by_me',
                          'organizing_committee',
                          'event_type',
                          'osp_achivement_certificate_path',
                          'international_achievement_Verification_certificate_path'
                          // add other desired columns
                      ])
                      ->with([
                        'tournament' => function ($t) {
                            $t->select([
                                'id',
                                'tournament', // or whatever columns you want
                                // Add more tournament fields as needed
                            ]);
                        },
                        'game' => function ($g) {
                            $g->select([
                                'id',
                                'name', // replace with actual column names
                            ]);
                        },
                        'disabilityType' => function ($d) {
                            $d->select([
                                'id',
                                'type', // replace with actual column names
                            ]);
                        }
                    ]);
                  }])
                  ->with(['educationHosp' => function ($q) {
                    $q->select([
                        'id',
                        'application_id',
                        'other_qualification',
                        'qualification',
                        'certificate_path',
                    ]);
                }])
                ->with(['declarationsHosp' => function ($q) {
                    $q->select([
                        'id',
                        'application_id',
                        'declaration_id',
                        'declaration_file'
                    ]);
                }]);
                },
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'User details',
            'user' => collect($user)->map(function ($value) {
                return $value === null ? '' : $value;
            }),
        ]);
    }

    public function getPendingApplication(Request $request)
    {

        $user = $request->user(); // Assuming Sanctum auth

        $user = User::where('id', $user->id)->first();
        $userDetails = UserDetails::where('user_id', $user->id)->where('is_form_completed', 0)->first();
        $applicationId = $userDetails->application_id;
        $user->load([
            // Filter userDetails by application_id
            'applicationDetails' => function ($query) use ($applicationId) {
                $query->where('application_id', $applicationId);
            },
    
            // Filter sportsDisciplineHosp by application_id, and load its nested relations
            'sportsDisciplineHosp' => function ($query) use ($applicationId) {
                $query->where('application_id', $applicationId);
            },
            'sportsDisciplineHosp.tournament',
            'sportsDisciplineHosp.game',
            'sportsDisciplineHosp.disablilityType',
    
            // Same for education and declarations if needed
            'educationHosp' => function ($query) use ($applicationId) {
                $query->where('application_id', $applicationId);
            },
            'declarationsHosp' => function ($query) use ($applicationId) {
                $query->where('application_id', $applicationId);
            },
            'roles',
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => 'User details',
            'user' => collect($user)->map(function ($value) {
                return $value === null ? '' : $value;
            }),
        ]);
    }

    
    public function getEducationData(Request $request,$application_id)
    {
        $user = $request->user(); // Assuming Sanctum auth

        $event = EducationHOSP::where('user_id', $user->id)->where('application_id',$application_id)->get();

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
            'educations.*.otherText' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $index = explode('.', $attribute)[1];
                    $qualification = $request->input("educations.$index.qualification");
                    if ($qualification === 'Other' && is_null($value)) {
                        $fail("The otherText field is required when qualification is 'Other'.");
                    }
                },
            ],
            'educations.*.certificate' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!is_string($value) && !($value instanceof \Illuminate\Http\UploadedFile)) {
                        $fail('The certificate must be a string or a valid file.');
                    }
                },
                
            ],
            'educations.*.id' => 'nullable|integer',
        ]);
        

        $userId = $request->user()->id;
        $incomingIds = [];
        $err_msg = 'document must not exceed 2 MB.';

        // loop for size validation 
        foreach ($request->educations as $item) {
            if (isset($item['certificate']) && $item['certificate'] instanceof \Illuminate\Http\UploadedFile) {
                $validationResponse = $this->validateFileSize(
                    $item['certificate'],
                    2048, // Max size in KB
                    $item['qualification']. ' certificate '. $err_msg
                );
                if (isset($validationResponse) && $validationResponse) {
                    return $validationResponse;
                } 
            }
        }

        // loop for update or create new record
        foreach ($request->educations as $item) {
            $education = isset($item['id'])
                ? EducationHOSP::where('id', $item['id'])->where([['user_id', $userId],['application_id', $request->application_id]])->first()
                : new EducationHOSP();

            if (!$education) {
                $education = new EducationHOSP();
                // $education->user_id = $userId;
            }
            $education->user_id = $userId;
            $education->qualification = $item['qualification'];
            $education->other_qualification = $item['otherText'] ?? null;
            
            // Only replace file if new one is uploaded
            if (isset($item['certificate']) && $item['certificate'] instanceof \Illuminate\Http\UploadedFile) {
                $path = $item['certificate']->store('education-certificates', 'public');
                $education->certificate_path = basename($path);
            }

            $application_id = $item['application_id'];
            $education->application_id = $item['application_id'];
            $education->save();
            $incomingIds[] = $education->id;
        }

        // Optional: remove deleted items
        EducationHOSP::where('application_id', $application_id)
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
            'osp_achivement_certificate_path' => 'required|nullable',
        ]);
       
        $user =  $request->user();

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
            'application_id' => $request->application_id,
            'user_id' => $user->id,
        ];
        $err_msg = 'document must not exceed 2 MB.';
        if ($request->hasFile('disability_doc')) {
            $validationResponse = $this->validateFileSize(
                $request->file('disability_doc'),
                2048, // Max size in KB
                'Disability certificate/'. $err_msg
            );
            if (isset($validationResponse) && $validationResponse) {
                return $validationResponse;
            } 
            $pathDisability = $request->hasFile('disability_doc')
                ? $request->file('disability_doc')->store('certificates', 'public')
                : null;
            $updateData['disability_doc'] = basename($pathDisability);
        }
        if ($request->hasFile('osp_achivement_certificate_path')) {
            $validationResponse = $this->validateFileSize(
                $request->file('osp_achivement_certificate_path'),
                2048, // Max size in KB
                'OSP achivement certificate/'. $err_msg
            );
            if (isset($validationResponse) && $validationResponse) {
                return $validationResponse;
            } 
            $pathCertificate = $request->file('osp_achivement_certificate_path')->store('certificates', 'public');
            $updateData['osp_achivement_certificate_path'] = basename($pathCertificate);
        }
        if ($request->hasFile('international_achievement_Verification_certificate_path')) {

            $validationResponse = $this->validateFileSize(
                $request->file('international_achievement_Verification_certificate_path'),
                2048, // Max size in KB
                'International achievement Verification certificate/'. $err_msg
            );
            if (isset($validationResponse) && $validationResponse) {
                return $validationResponse;
            } 
            $pathCertificate = $request->file('international_achievement_Verification_certificate_path')->store('certificates', 'public');
            $updateData['international_achievement_Verification_certificate_path'] = basename($pathCertificate);
        }
        SportsDisciplineHosp::updateOrCreate(
            [
                'application_id' => $request->application_id,
            ],
            $updateData
        );


        return response()->json(['message' => 'Saved successfully'], 201);
    }
    public function getSportDiscipline(Request $request,$application_id)
    {
        $user = $request->user();
        $data = $user->sportsDisciplineHosp()->with(['tournament', 'game','disablilityType'])->latest()->first();
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
        $err_msg = 'document must not exceed 2 MB.';
        if ($request->hasFile('declaration_file')) {
           
            $validationResponse = $this->validateFileSize(
                $request->file('declaration_file'),
                2048, // Max size in KB
                'Signed Declaration '. $err_msg
            );
            if (isset($validationResponse) && $validationResponse) {
                return $validationResponse;
            } 
            // $filePath = $request->file('declaration_file')->store('declarations', 'public');
            $filePath = $request->file('declaration_file')->store('declarations', 'public');
            $filePath = basename($filePath);
        }

        // Optional: Clear old records if needed
        DeclarationsHosp::where([['user_id', $userId],['application_id',$request->application_id]])->delete();

        // Save declarations, attach file only once (e.g. to the first)
        foreach ($request->declaration_ids as $index => $declarationId) {
            DeclarationsHosp::create([
                'user_id' => $userId,
                'application_id' => $request->application_id,
                'declaration_id' => $declarationId,
                // 'declaration_file' =>  $index == 0 ? ($filePath ?? '') : null,
                'declaration_file' =>   $filePath ?? '',
            ]);
        }

        // update user details table after complete
        UserDetails::where([
            ['user_id', '=', $userId],
            ['application_id', '=', $request->application_id],
        ])->update([
            'is_form_completed' => 1,
            'active_step' => 5,
        ]);

        // create new record for new application of same user
        $existingUserDetails = UserDetails::where('user_id', $userId)
        ->where('application_id', $request->application_id)
        ->first();

        if ($existingUserDetails) {
            $newUserDetails = $existingUserDetails->replicate();

            // Override or remove unwanted columns
            $newUserDetails->is_form_completed = 0;      // reset
            $newUserDetails->active_step = 1;            // reset

            // Example: remove/ignore created_at, updated_at, or others
           
            unset($newUserDetails->application_id);
            unset($newUserDetails->photo);
            unset($newUserDetails->aadhaar);
            unset($newUserDetails->domicile);
            unset($newUserDetails->dob_doc);
            unset($newUserDetails->domicile_doc);
            unset($newUserDetails->played_national_level);
            unset($newUserDetails->national_level_doc);
            unset($newUserDetails->organisation_represented);
            unset($newUserDetails->organisation_doc);
            unset($newUserDetails->created_at);
            unset($newUserDetails->updated_at);

            $newUserDetails->save();
        }
        return response()->json(['message' => 'Declarations saved successfully.']);
    }

    public function getHospDeclarations(Request $request,$application_id)
    {
        $userId = $request->user()->id;

        $declarations = DeclarationsHosp::where('user_id', $userId)->where('application_id',$application_id)->with('declaration')->get();

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
