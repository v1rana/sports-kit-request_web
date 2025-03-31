@extends('layouts.master')

@section('title', 'Sports !! Create Account')

@section('content')

<section class="container login-area">
    <div class="row justify-content-center">
        <div class="col-10 bg-white border">
            <form method="POST" action="{{ route('register.store') }}">
            @csrf
                <div class="content">
                    <div class="step w-100" id="step1">
                        <div class="row justify-content-between">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                @if (session('success'))
									<p class="text-success" id="alert">{{ session('success') }}</p>
								@endif
                                <!--h2 class="haryana-logo"><img src="images/haryana-logo.png" class="w-25"> <span>Government of Haryana</span></h2-->
                                <h2>Create your account</h2>
                                <h6>for Gradation Certificate  for jobs</h6>
                                <p class="text-success">Enter your Name</p>
                                <p class="mt-5">Already have an account? <a href="{{ url('/') }}">Login</a></p>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-5">                            
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control required" id="first_name" name="first_name" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'');" require>
                                    <label for="floatingInput">First Name <sup class="text-danger">*</sup></label>
                                </div>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="last_name" name="last_name" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'');">
                                    <label for="floatingInput">Last Name (optional)</label>
                                </div> 
                            </div>
                        </div>
                    </div>
                    
                    <div class="step w-100" id="step2">
                        <div class="row justify-content-between">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <!--h2 class="haryana-logo"><img src="images/haryana-logo.png" class="w-25"> <span>Government of Haryana</span></h2-->
                                <h2>Basic Information</h2>
                                <p class="text-success mb-4">Enter your DOB</p>
                                <p class="mt-5">Already have an account? <a href="{{ ('/') }}">Login</a></p>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-5">                            
                                <div class="d-flex ">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control required" id="dob_month" maxlength="2" name="dob_month" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        <label for="floatingInput">Month <sup class="text-danger">*</sup></label>
                                    </div>
                                    <div class="form-floating mb-3 mx-3">
                                        <input type="text" class="form-control required" id="dob_day" maxlength="2" name="dob_day" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        <label for="floatingInput">Day <sup class="text-danger">*</sup></label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control required" id="dob_year" maxlength="4" name="dob_year" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        <label for="floatingInput">Year <sup class="text-danger">*</sup></label>
                                    </div>
                                </div>
                                <div class="form-floating mb-3 mt-3">
                                    <select class="form-control required" id="gender" name="gender">
                                        <option value="">--Select--</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    <label for="floatingInput">Gender <sup class="text-danger">*</sup></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="step w-100" id="step3">
                        <div class="row justify-content-between">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <!--h2 class="haryana-logo"><img src="images/haryana-logo.png" class="w-25"> <span>Government of Haryana</span></h2-->
                                <h2>Contact Information</h2>
                                <p class="text-success mb-4">Enter your details</p>
                                <p class="mt-5">Already have an account? <a href="{{ ('/') }}">Login</a></p>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-5">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control required" id="email" name="email" oninput="validateGmail(this)">
                                    <label for="floatingInput">Email Id <sup class="text-danger">*</sup></label>
                                    <p id="error-message" style="color: red;"></p>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control required" id="mobile" name="mobile" maxlength="10" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                    <label for="floatingInput">Mobile number <sup class="text-danger">*</sup></label>
                                    <small class="text-danger">NOTE - OTP will be sent to entered mobile number</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="step w-100 otp-area" id="step4">
                        <div class="row justify-content-between">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <!--h2 class="haryana-logo"><img src="images/haryana-logo.png" class="w-25"> <span>Government of Haryana</span></h2-->
                                <h2>Login </h2>
                                <p class="text-success mb-4">Enter your OTP verification code</p>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-5">
                            <h2>Enter OTP</h2>
                            <p>SMS has been sent to your mobile number ******999</p>
                           <div class="input-verify-otp"><input type="text" class="form-control mb-2 required" maxlength="6" id="otp" name="otp" placeholder="Enter OTP" required oninput="this.value=this.value.replace(/[^0-9]/g,'');"><a type="button" id="verifyOtpBtn" class="text-green btn btn-success">Verify OTP</a></div>
                            <p id="otperror" class="alert alert-danger mb-0" style="display:none;">Please enter OTP</p>
                            <p id="otpInvalid" class="alert alert-danger mb-0" style="display:none;">Invalid OTP or expired.</p>
                            <p id="verified" class="alert alert-success mb-0" style="display:none;">OTP verified successfully</p>
                            <p id="resenderror" class="alert alert-success mb-0" style="display:none;">OTP sent successfully</p>
                            <p class="mt-2"> Didn't receive OTP? <a href="#" id="resendOtp">Resend code</a></p>
                            <span id="countdown"></span>
                            </div>
                        </div>
                    </div>
                </div>					
                <div class="w-100 text-end mt-2 pt-3 border-top">
                    <a id="previousButton" class="btn btn-secondary" style="display:none;">Previous</a>
                    <a id="nextButton" class="btn btn-primary">Next</a>
                    <button id="submitButton" type="submit" class="btn btn-success" style="display:none;">Submit</button>
                    
                </div>
            </form>
        </div>
    </div>
</section>

@endsection