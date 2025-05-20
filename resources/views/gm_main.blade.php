<!DOCTYPE html>
<html>

<head>
    <title>Equipment Request Form - Sports Haryana </title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content=""> 
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Haryana Sports Equipment')</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="{{ url('assets/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/main-style.css') }}" />
	
    <script src="{{ url('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</head>
<body>
<header class="container-fluid">
		<div class="row justify-content-between py-2">
			<div class="col-10">
				<div class="logo">
					<a href="#" title="Go to home" class="site_logo" rel="home">
						<img class="" id="logo" src="{{ url('assets/images/logo-sports.png') }}" alt="Sports Haryana Govt">
						<div class="logo_text">
							<strong lang="">खेल विभाग हरियाणा</strong>
                            <h1 class="h1-logo">Sports Department , Government of Haryana</h1>
							<span class="logo-sub-title">Let the young minds grow to the full potential</span>
						</div>
					</a>
				</div>
			</div>
			<div class="col-2 text-end">
				<img class="w-75" src="{{ url('assets/images/DigitalIndia.png') }}" alt="Sports Haryana Govt" style="filter:invert(1)">
				<a href="{{ url('/logout') }}" class="btn btn-danger"><i class="fa-solid fa-power-off"></i> Log Out</a>
				
			</div>
		</div>
	</header>
	<div class="main">
    
    <div class="d-flex">
       
        <!-- SideBar- aside sec Ends -->
        <div class="content-area">
			<!--<header>
				<div class="container-fluid">
					<div class="row justify-content-between border-bottom align-items-center">
						<div class="col-6 logo d-flex">
							
						</div>
						<div class="col-6 text-end">
							<a href="{{ url('/logout') }}" class="btn btn-danger"><i class="fa-solid fa-power-off"></i> <span>Log Out</span></a>
						</div>
						
					</div>
				</div>
			</header>-->
        <div class="container mt-4">
            @yield('content')
        </div>
    </div>
    <!--<footer>
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
			
	<link rel="stylesheet" href="{{ url('assets/css/main-style.css') }}" />		
</body>
</html>
