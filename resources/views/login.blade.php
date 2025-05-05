@extends('layouts.master')
@section('title', 'Sports !! Login Page')
@section('content')
@if (session('success'))
<div class="alert alert-success" id="alert" style="margin-left: 220px;margin-right: 220px;">
   {{ session('success') }}
</div>
@endif
<style>
   .awesome-links {
   background: linear-gradient(135deg, #16617e, #1c6979);
   color: #fff;
   border-radius: 70px 0px;
   transition: transform 0.3s ease;
   }
   /* .awesome-links:hover {
   transform: translateY(-5px);
   } */
   .link-custom {
   color: #fff;
   font-weight: 600;
   text-decoration: underline;
   margin-left: 5px;
   }
   .link-custom:hover {
   color: #000;
   text-decoration: none;
   }
   .form-container {
   background-color: rgba(255, 255, 255, 0.85);
   padding: 2rem;
   border-radius: 15px;
   box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
   max-width: 600px;
   width: 100%;
   /* margin: auto; */
   }
   .form-container h2,
   .form-container h5 {
   color: #404ee8;
   font-weight: bold;
   text-align: center;
   }
</style>
<section class="new-hero mb-4" id="my-section">
   <div class="form-container">
      <div class="dep-logo text-center mb-3">
         <img src="{{ url('assets/job_app/images/logo-sports.png') }}" alt="Department Logo">
      </div>
      <h5>Sports Department</h5>
      <p class="text-center fw-bold" style="font-size: 14px">Let the young minds grow to the full potential</p>
      <h2>Login Page</h2>
      <form method="POST" action="{{ route('sports.login') }}">
         @csrf
         <div class="step" id="step1">
            @if (session('message'))
            <p class="text-danger" id="alert">{{ session('message') }}</p>
            @endif
            @if (session('otp'))
            <p class="text-danger" id="alert">{{ session('otp') }}</p>
            @endif
            <p class="fw-bold mb-2">For Official Login</p>
            <div class="form-floating mt-4 mb-3">
               <input type="text" class="form-control required" id="mobile" name="mobile" maxlength="10"
                  oninput="this.value=this.value.replace(/[^0-9]/g,'');">
               <label for="floatingInput">Mobile number</label>
            </div>
            <p class="text-secondary mb-0">We will send you a verification code</p>
         </div>
         <div class="step" id="step2" style="display: none;">
            <h2>Enter OTP</h2>
            <p>SMS has been sent to your mobile number ******</p>
            <input type="text" class="form-control mb-2 required" maxlength="6" id="otp" name="otp"
               placeholder="Enter OTP" required>
            <p id="otperror" class="text-danger" style="display:none;">Please enter OTP</p>
            <p id="otpInvalid" class="text-danger" style="display:none;">Invalid OTP or expired.</p>
            <p id="verified" class="text-success" style="display:none;">OTP verified successfully</p>
            <p id="resenderror" class="text-success" style="display:none;">OTP sent successfully</p>
            <p class="mt-2">Didn't receive OTP? <a href="#" class="resendOtp">Resend code</a></p>
         </div>
         <div class="text-end mt-3">
            <a id="previousButton" class="btn btn-secondary" style="display:none;">Previous</a>
            <a id="sendotp" class="btn btn-primary">Send OTP</a>
            <button id="submitButton" type="submit" class="btn btn-success" style="display:none;">Submit</button>
         </div>
      </form>
      <div class="form-box">
         <h5>Select Sports Category</h5>
         <div class="awesome-links p-4 mb-3 text-center">
            <!--<p class="mb-2">
               Don’t have an account?
               <a href="{{ url('/create-account') }}" class="link-custom">Create an account</a>
               </p>-->
            <p class="m-0">
               Haryana Outstanding Sports Persons Jobs
               <!-- <a href="{{ url('login') }}" class="link-custom">Apply Here</a> -->
               <a href="http://164.100.137.70/hosp/login" class="link-custom">Apply Here</a>
            </p>
         </div>
         <div class="row">
            <div class="col-md-6">
               <div class="mb-3 form-check">
                  <input class="form-check-input" type="radio" name="sportsOption" id="equipment">
                  <label class="form-check-label" for="equipment">Haryana Sports Equipment</label>
               </div>
               <div class="mb-3 form-check">
                  <input class="form-check-input" type="radio" name="sportsOption" id="gradation">
                  <label class="form-check-label" for="gradation">Haryana Sports Gradation</label>
               </div>
               <!-- <div class="mb-4 form-check">
                  <input class="form-check-input" type="radio" name="sportsOption" id="person">
                  <label class="form-check-label" for="person">Haryana Outstanding Sports Person</label>
                  </div> -->
            </div>
            <div class="col-md-6">
               <div class="mb-3">
                  <label for="pppId" class="form-label">PPP ID</label>
                  <input type="text" class="form-control" id="pppId" value="1KQP3440">
               </div>
               <button class="btn btn-custom">Display Members</button>
            </div>
         </div>
      </div>
      <style>
         .form-box {
         max-width: 600px;
         margin: 20px auto;
         background: white;
         padding: 20px;
         border-radius: 15px;
         box-shadow: 0 10px 25px rgba(0,0,0,0.1);
         }
         .form-check-label {
         font-weight: 600;
         color: #2c3e50;
         }
         .form-control {
         border-radius: 10px;
         height: 45px;
         }
         .btn-custom {
         background: #00b894;
         color: white;
         padding: 10px 25px;
         border-radius: 10px;
         font-weight: bold;
         transition: background 0.3s ease;
         }
         .btn-custom:hover {
         background: #019875;
         }
         h5 {
         font-weight: bold;
         color: #4834d4;
         }
      </style>
   </div>
</section>

<script>


window.addEventListener("load", function () {
    const section = document.getElementById("my-section");
    section.scrollIntoView({ behavior: "auto", block: "center" });
  });



   document.getElementById('sendotp').addEventListener('click', function(e) {
       e.preventDefault();
       let mobile = document.getElementById('mobile').value;
       if (mobile.length === 10) {
           document.getElementById('step1').style.display = 'none';
           document.getElementById('step2').style.display = 'block';
           document.getElementById('sendotp').style.display = 'none';
           document.getElementById('submitButton').style.display = 'inline-block';
           document.getElementById('previousButton').style.display = 'inline-block';
       } else {
           alert('Please enter a valid 10-digit mobile number');
       }
   });
   
   document.getElementById('previousButton').addEventListener('click', function(e) {
       e.preventDefault();
       document.getElementById('step1').style.display = 'block';
       document.getElementById('step2').style.display = 'none';
       document.getElementById('sendotp').style.display = 'inline-block';
       document.getElementById('submitButton').style.display = 'none';
       document.getElementById('previousButton').style.display = 'none';
   });
   
   document.querySelector('.resendOtp')?.addEventListener('click', function(e) {
       e.preventDefault();
       // AJAX call can be implemented here if needed
       document.getElementById('resenderror').style.display = 'block';
   });
</script>
@endsection