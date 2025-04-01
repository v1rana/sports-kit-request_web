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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
	<div class="main">
    
    <div class="d-flex">
        @include('layouts.gm_sidebar')  <!-- ✅ Sidebar Included -->
        
        <div class="container mt-4">
            @yield('content')
        </div>
    </div>
    <footer>
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
			</footer>
</body>
</html>
