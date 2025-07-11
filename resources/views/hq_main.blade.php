<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Sports Haryana </title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content=""> 
    <meta name="author" content="">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Haryana Sports Equipment')</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="shortcut icon" href="{{ url('assets/job_app/dash/images/logo-sports.png') }}" />


    <link rel="stylesheet" href="{{ url('assets/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/main-style.css') }}" />
    <script src="{{ url('assets/js/jquery.min.js') }}" ></script>
    <script src="{{ url('assets/bootstrap/js/bootstrap.bundle.min.js') }}" ></script>
</head>
<body>
	<div class="main">
    
    <div class="d-flex">
        @include('layouts.hq_sidebar')  <!-- ✅ Sidebar Included -->
        
        <div class="container mt-4">
            @yield('content')
        </div>
    </div>
   <!-- <footer>
				<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-xs-12 col-sm-8">
							<p>All rights reserved. Powered by <strong>Citizen Resources Information Department, Haryana</strong></p>
						</div>
						<div class="col-xs-12 col-sm-4 text-end">
							<div class="visitor-counter">
							
								<strong>Visitor Count</strong> <span>130361</span>
							</div>
						</div>
					</div>
				</div>
			</footer>-->
			 <footer class="footer-custom bg-dark text-white py-3 mt-auto">
  <div class="container-fluid">
    <div class="row align-items-center text-center text-sm-start">
      
      <!-- Left Side: Text -->
      <div class="col-12 col-sm-8 mb-2 mb-sm-0">
        <p class="mb-0 small">
          All rights reserved. Powered by <strong>Citizen Resources Information Department, Haryana</strong>
        </p>
      </div>
      
      <!-- Right Side: Visitor Counter -->
      <div class="col-12 col-sm-4 text-sm-end">
        <div class="visitor-counter small">
          <i class="fa-solid fa-eye me-1"></i> <strong>Visitor Count:</strong> <span class="badge bg-primary">130361</span>
        </div>
      </div>
    </div>
  </div>
</footer>
<style>
.footer-custom {
  border-top: 2px solid #444;
  font-size: 0.95rem;
  box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
  position: relative;
  bottom: 0;
  width: 100%;
}

.visitor-counter span {
  font-weight: 600;
  padding: 3px 8px;
  border-radius: 12px;
}
</style>
</body>
</html>
