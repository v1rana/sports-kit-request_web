<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\GramPanchayatSarpanch;
use App\Models\MunicipalBodyMember;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.create_account');
    }

    public function register(Request $request)
    {
        // Validate user input
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);

        // Automatically log in the user
        auth()->login($user);

        return redirect()->route('sports_kit.dashboard')->with('success', 'Registration successful!');
    }

    public function sendOTP(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
        ]);

        $mobile = $request->mobile;

        // Check if user exists in either table
        $user = GramPanchayatSarpanch::where('mob', $mobile)->first() ??
                MunicipalBodyMember::where('mob', $mobile)->first();

        if (!$user) {
            return back()->withErrors(['mobile' => 'Mobile number not registered.']);
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        $expiryTime = now()->addMinutes(10); // OTP valid for 10 minutes

        // Store OTP in database
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => $expiryTime
        ]);

        // Store OTP in session for verification
        Session::put('otp', $otp);
        Session::put('mobile', $mobile);

        // Simulating OTP sending (Replace with actual SMS API)
        Log::info("OTP for {$mobile} is: {$otp}");

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otp // Remove this in production for security reasons
        ]);
        //return redirect()->route('verify.otp.form')->with('success', 'OTP sent to your mobile.');
    }

    public function showVerifyForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOTP(Request $request)
{
    $request->validate([
        'otp' => 'required|digits:6',
    ]);

    $mobile = Session::get('mobile');

    // Check if user exists in Gram Panchayat or Municipal
    $user = GramPanchayatSarpanch::where('mob', $mobile)->first() ?? 
            MunicipalBodyMember::where('mob', $mobile)->first();

    if (!$user || $request->otp != $user->otp) {
        return response()->json(['success' => false, 'message' => 'Invalid OTP'], 401);
    }

    // OTP verified, clear from DB
    $user->update(['otp' => null, 'otp_expires_at' => null]);

    // Store session
    Session::put('logged_in_user', [
        'name' => $user instanceof GramPanchayatSarpanch ? $user->sarpanch : $user->wardmember,
        'mobile' => $mobile,
        'role' => $user instanceof GramPanchayatSarpanch ? 'Gram Panchayat' : 'Municipal',
    ]);

    Session::forget(['otp', 'mobile']);

    return response()->json(['success' => true]); // ✅ JSON Response for JavaScript
}


    public function logout()
    {
        Session::forget('logged_in_user');
        return redirect()->route('login');
    }
}
