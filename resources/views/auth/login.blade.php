<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sports Department - Citizen Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
  

    .bg-hero {
      background: url('./assets/images/new-bann.jpg') no-repeat center center/cover;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .bg-overlay {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      z-index: 1;
    }

    .content-box {
    position: relative;
    z-index: 2;
    background: rgb(255 255 255 / 14%);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    padding: 40px;
    width: 90%;
    max-width:600px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    color: white;
    }

    .logo-title h1 {
      font-size: 2.1rem;
      font-weight: bold;
      color: #d11e3a;
    }

    .tagline {
      font-size: 1.2rem;
      margin-bottom: 20px;
      color: #e0e0e0;
    }

 /* Form section style */
 .section-title {
  font-size: 1.6rem;
  font-weight: bold;
  color: #d11e3a;
  margin-bottom: 30px;
  position: relative;
  text-align: center; 
  text-shadow: 1px 1px 2px rgba(0,0,0,0.4);
}

.section-title::before {
  content: "";
  position: absolute;
  bottom: -5px; 
  left: 50%;
  transform: translateX(-50%); 
  width: 150px; 
  height: 3px;
  background-color: #d11e3a;
  border-radius: 2px;
}


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

.form-label {
  font-size: 1.1rem;
  color: #fff;
}

.form-control {
  border-radius: 12px;
  padding: 7px 12px;
  font-size: 1rem;
  border: none;
  background-color: rgba(255, 255, 255, 0.2);
  color: #fff;
  box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
}

.form-control::placeholder {
  color: #ffffff;
}

.btn-custom {
  background-color: #d11e3a;
  color: #ffffff;
  font-weight: 600;
  border-radius: 30px;
  padding: 7px 30px;
  transition: 0.3s ease;
  width: 100%;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
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
  </style>
</head>
<body>

  <div class="bg-hero">
    <div class="bg-overlay"></div>
    <div class="content-box">
      <div class="text-center logo-title mb-4">
        <img src="./assets/images/logo-sports.png" alt="Department Logo" class="mb-3" style="width: 110px; height: auto;">
        <h1>Sports Department</h1>
        <p class="tagline">Let the young minds grow to the full potential</p>
      </div>
      

      <div class="section-title text-center">For Official Login</div>

      <div class="row">
       
       

        <div class="col-md-12">
		<form method="POST" action="{{ route('send.otp') }}">
                @csrf
          <div class="mb-2">
            <label for="pppId" class="form-label mb-0">Mobile Number</label>
            <input type="text" class="form-control" id="pppId" placeholder="Enter your mobile no.">
            <small class="form-text" style="color: #ff5b75;">We will send you a verification code.</small>
          </div>
          
          <button class="btn btn-custom mt-1">Send OTP</button>
		  </form>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
