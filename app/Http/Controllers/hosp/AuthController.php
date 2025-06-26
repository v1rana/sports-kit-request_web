<?php

namespace App\Http\Controllers\hosp;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // return response()->json([
        //     'id' => 1,
        //     'name' => 'test user',
        //     'pppId' => $request->pppId,
        // ]);
        // $data = $request->only('memberID', 'mobileNo');
        $data = $request->all();

        $request->validate([
            'memberID' => 'required|string',
            'mobileNo' => 'required|string',
        ]);
    
        // Update or create user
        $user = User::firstOrCreate(
            ['member_id' => $request->memberID],
            [
                'mobile' => $request->mobileNo,
                'name' => $request->fullName,
                'email' => $request->email,
            ]
        );
        $dateOfBirth = \Carbon\Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');
        $user_details = UserDetails::updateOrCreate(
            ['user_id' => $user->id],
            [
                'family_id' => $request->familyID,
                'full_name_en' => $request->fullName,
                'full_name_hi' => $request->fullNameLL,

                'father_name_en' => $request->fatherFullName,
                'father_name_hi' => $request->fatherFullNameLL,
                'mother_name_en' => $request->motherFullName,
                'mother_name_hi' => $request->motherFullNameLL,
                'date_of_birth' => $dateOfBirth,
                'age' => $request->age,
                'gender' => $request->gender,
                'marital_status' => $request->maritalStatus,
                'address_landMark' => $request->address_LandMark,
                'district' => $request->districtName,
                'block_town' => $request->btName,
                'ward_village' => $request->wvName,
                'pincode' => $request->pinCode,
                'email_id' => $user->email,
                'benchmark_disability' => $request->disabiltyType,
                'caste_category' => $request->casteCategoryName,
                'highest_qualification' => $request->qualificationName,
                'current_engagement' => $request->isEngagementVerified,
                'annual_income' => $request->totalIncome,
                'income_verified' => $request->isIncomeVerified == 'Y' ? 1 : 0,
                
            ]
        );
        // Eager load after creating/updating user
        $user->load('userDetails','sportsDisciplineHosp','declarationsHosp');
        // Log in
        $token = $user->createToken('api-token')->plainTextToken;
        $encryptedId = Crypt::encryptString($user->id);
        return response()->json([
            'user' => $user,
            'token' => $token,
            'userId' => $encryptedId,
        ]);
       
        // Auth::login($user);
        // $request->session()->regenerate();
    
        // return response()->json([
        //     'message' => 'Login successful',
        //     'user' => $user,
        // ]);
    }

    public function getMemberbasicdetailsfromFIDUID(Request $request)
    {
        // Optional: Get UIDFID from request if dynamic
        $uidfid = $request->input('uidfid', '1KQP3440');


        $payload = [
            "DeptCode"     => "SPT",
            "ServiceCode"  => "CAW",
            "DeptKey"      => "0A5CDE2406",
            "UIDFID"       =>  $uidfid
        ];
    
        $curl = curl_init();
    
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://pppapi.edisha.gov.in:8443/api/Account/GetMemberbasicdetailsfromFIDUID",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false, // Optional: skip SSL verification (use only in dev)
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
        ]);
    
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
    
        if ($err) {
            return response()->json([
                'error' => 'cURL Error',
                'message' => $err
            ], 500);
        } else {
            return response()->json(json_decode($response, true));
        }
    
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://pppapi.edisha.gov.in:8443/api/Account/GetMemberbasicdetailsfromFIDUID', [
                'DeptCode'     => 'SPT',
                'ServiceCode'  => 'CAW',
                'DeptKey'      => '0A5CDE2406',
                'UIDFID'       => $uidfid,
            ]);
    
            // Check if the request was successful
            if ($response->successful()) {
                return response()->json($response->json(), 200);
            }
    
            // If the response failed
            return response()->json([
                'error' => 'API request failed.',
                'status' => $response->status(),
                'body' => $response->body()
            ], $response->status());
    
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json([
                'error' => 'Request failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
