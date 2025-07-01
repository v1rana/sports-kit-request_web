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
        try {
            $uidfid = $request->input('uidfid', '6vdc9173');
            $url =  'https://pppapi.edisha.gov.in:8443/api/Account/GetMemberbasicdetailsfromFIDUID';
            $parameters = [
                'DeptCode' => 'SPT',
                'Servicecode' => 'CAW',
                'DeptKey' => '0A5CDE2406',
                // 'UIDFID' => '1KQP3440',
                'UIDFID' => $uidfid,
            ];
            $response = Http::post( $url, $parameters );
            if ( $response->successful() ) {
                $data = $response->json();
                return response()->json( [
                    'success' => true,
                    'data' => $data->data
                ] );
            } else {
                return response()->json( [
                    'success' => false,
                    'message' => 'Failed to fetch member details',
                    'error' => $response->body()
                ], $response->status() );
            }
        }catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json([
                'error' => 'Request failed',
                'message' => $e->getMessage()
            ], 500);
        }
      
    }
}
