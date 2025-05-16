<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sports Department - Citizen Login</title>
  <link rel="stylesheet" href="{{ url('assets/job_app/bootstrap/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ url('assets/job_app/css/login-style.css') }}" />
  <style>
    /* Form section style */
    .form-check {
      background: rgba(255, 255, 255, 0.05);
      padding: 15px 20px;
      border-radius: 12px;
      margin-bottom: 15px;
      border: 1px solid rgba(255,255,255,0.2);
    }

    .form-check:hover {
      background: rgba(255, 255, 255, 0.15);
    }

    .form-check-label {
      font-size: 1.1rem;
      color: #fff;
      margin-left: 10px;
      cursor: pointer;
    }

    .form-check-input {
      width: 20px;
      height: 20px;
      margin-top: 3px;
    }

    .form-check .form-check-input {
      float: unset;
      margin-left: 0;
    }

    .form-control::placeholder {
      color: #ffffff;
    }

    .btn-custom:hover {
      background-color: #ed2f4c;
      transform: translateY(-2px);
    }

    @media (max-width: 767px) {
      .logo-title h1 {
        font-size: 1.9rem;
      }
      .tagline {
        font-size: 1rem;
      }
    }

    /* Hide OTP input initially */
    #otpField {
      display: none;
    }

    .section-title {
	font-size: 16px;
	margin-bottom: 20px;
	font-weight: bold;display: flex	;
		width: 100%;
		text-align: center !important;
		justify-content: center;
  }
  .section-title a {text-decoration:none;font-weight:normal;
    display: block;
    border: 1px dotted;
    margin: 0 2px;
    padding: 4px 10px;font-size:13px;text-transform:uppercase;
    border-radius: 5px;
    color: rgba(0, 0, 0, 0.3);
    background: #eee;
}.section-title a.active-login {
    color: #fff;
    background: blue;
    border-color: blue;
}
  </style>
</head>
<body>
  <div class="background-image"></div>

  <div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="row login-container">

      <!-- Left Side Form -->
      <div class="col-md-5 left-form bg-white">
        <div class="text-center logo-title mb-4">
          <img src="./assets/images/logo-sports.png" alt="Logo">
          <h1>Sports Department</h1>
          <p class="tagline">Let the young minds grow to the full potential</p>
        </div>

        <div class="section-title text-center">
                          
          <a href="/" >Applicant Login</a> 
      
         
           <a href="/login" class="active-login"> Offical Login</a>
       </div>
        <form method="POST" action="{{ route('send.otp')}}">
          @csrf
        <div class="mb-3">
          <label for="pppId" class="form-label">Mobile Number</label>
          <input type="text" class="form-control" id="mob" name="mobile" placeholder="Enter your mobile no." maxlength='10' required>
          <small class="form-text" style="color: #ff5b75;">We will send you a verification code.</small>
          <button type="submit" class="btn btn-custom mt-1" id="sendOtpBtn">Send OTP</button>
        </div>
          <!-- OTP Input field, initially hidden -->
          <div class="mb-2" id="otpField">
            <label for="otp" class="form-label mb-0">Enter OTP</label>
            <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP" required>
          </div>

          <!-- Send OTP button, triggers OTP input visibility -->
          {{-- <button type="submit" class="btn btn-custom mt-1" id="sendOtpBtn">Send OTP</button> --}}

          <!-- Submit button to submit form after OTP entry -->
          <button type="button" class="btn btn-custom mt-1" id="submitBtn" style="display: none;">Submit</button>

    
      </div>

      <!-- Right Side Content -->
      <div class="col-md-7 right-side">
        <div class="testimonial-text">
          "Empowering athletes through seamless digital access and support."
        </div>
        <div class="testimonial-author">
          Haryana Sports Department
        </div>
      </div>

    </div>
  </div>
  <div class="background-image"></div>
  <div class="background-overlay"></div>

  

  <script src="{{ url('assets/job_app/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

  <script>
document.addEventListener('DOMContentLoaded', function () {
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const otpField = document.getElementById('otpField');
    const submitBtn = document.getElementById('submitBtn');

    sendOtpBtn.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent form submit

        const mobile = document.getElementById('mob').value;

        fetch("{{ route('send.otp') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": '{{ csrf_token() }}'
            },
            body: JSON.stringify({ mobile })
        })
        .then(response => response.json())
        .then(data => {
			console.log(data);
            if (data.success) {
                alert("OTP has been sent to your mobile number.");
                otpField.style.display = 'block';
                submitBtn.style.display = 'inline-block';
                sendOtpBtn.style.display = 'none';
            } else {
                alert(data.message || "Failed to send OTP.");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Mobile Number Not Registered.");
        });
    });
});
</script>
<script>
submitBtn.addEventListener('click', function () {
    const mobile = document.getElementById('mob').value;
    const otp = document.getElementById('otp').value;

    fetch("{{ route('verify.otp') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": '{{ csrf_token() }}'
        },
        body: JSON.stringify({ mobile, otp })
    })
    .then(response => response.json())
    .then(data => {
		console.log(data);
        if (data.success) {
            alert("Login successful! Redirecting...");
            window.location.href = data.redirect_to;
        } else {
            alert(data.message || "OTP verification failed.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Mobile Number Not Registered.");
    });
});


</script>

</body>
</html>
