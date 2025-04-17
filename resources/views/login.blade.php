@extends('layouts.master')

@section('title', 'Sports !! Login Page')

@section('content')
@if (session('success'))
	<div class="alert alert-success" id="alert" style="margin-left: 220px;margin-right: 220px;">
        {{ session('success') }}
    </div>
@endif	
<section class="container login-area">
		<div class="row justify-content-center">
			<div class="col-10 bg-white border ">
				<div class="content">
				    <form method="POST" action="{{ route('sports.login') }}">
                    @csrf
					<div class="step w-100" id="step1">
						<div class="row justify-content-between">
							<div class="col-xs-12 col-sm-6 col-md-6">							
								<h2>Sign in</h2>
								<h6>to continue with your application</h6>
								<p class="mt-5">Didn't have an account? <a href="{{ url('/create-account') }}"> - Create an account</a></p>
								<p class="mt-2"> Haryana Outstanding Sports Persons Jobs  <a href="{{ url('/hosp/login') }}"> - Apply Here</a></p>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-5">
						        	@if (session('message'))
									<p class="text-danger" id="alert">{{ session('message') }}</p>
									@endif
									@if (session('otp'))
									<p class="text-danger" id="alert">{{ session('otp') }}</p>
									@endif
									<p class="fw-bold mb-1">Click Here to Apply for Sports Gradation Certificate and Haryana Provision of Sports Equipment Requisition</p><br>
									<div class="form-floating">
									<input type="text" class="form-control required" id="mobile" name="mobile" maxlength="10" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
										<label for="floatingInput">Mobile number</label>
									</div>
									<p class="mt-1 text-secondary">We will send you a verification code</p>
								
							</div>
						</div>
					</div>
					
					<div class="step w-100" id="step2">
						<div class="row justify-content-between">
							<div class="col-xs-12 col-sm-6 col-md-6">							
								<h2>Sign in</h2>
								<h6>Enter OTP sent to your registered<br/> Mobile number</h6>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-5">								
								<h2>Enter OTP</h2>
								<p>SMS has been sent to your mobile number ******</p>
								<input type="text" class="form-control mb-3 required" maxlength="6" id="otp" name="otp" placeholder="Enter OTP" required>
								<p id="otperror" style="display:none; color: red;">Please enter OTP</p>
								<p id="otpInvalid" style="display:none; color: red;">Invalid OTP or expired.</p>
								<p id="verified" style="display:none; color: green;">OTP verified successfully</p>
								<p id="resenderror" style="display:none; color: green;">OTP sent successfully</p>
								Didn't receive OTP? <a href="#" class="resendOtp">Resend code</a>                            								
							</div>
						</div>
					</div>
					<div class="col-12 text-end mt-2 pt-3 border-top">
						<a id="previousButton" class="btn btn-secondary" style="display:none;">Previous</a>
						<a id="sendotp" class="btn btn-primary">Send OTP</a>
						<button id="submitButton" type="submit" class="btn btn-success" style="display:none;">Submit</button>
					</div>
					</form>	
				</div>
			</div>
		</div>
	</section>
@endsection