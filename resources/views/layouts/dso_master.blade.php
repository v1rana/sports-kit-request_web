<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content=""> 
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ url('assets/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/login-style.css') }}" />
	
</head>
<body>

@include('layouts.dso_header')

<main>
    @yield('content')
</main>

@include('layouts.footer')

</body>
</html>