<!-- SideBar- aside sec -->
<aside class="collapse show" id="navbarToggleExternalContent">
    <div class="d-flex align-items-center justify-content-between text-white logo-area">
        <div class="logo">
            <a href="#" title="Go to home" class="site_logo" rel="home">
                <img class="" id="logo" src="{{ url('assets/images/logo-sports.png') }}" alt="Sports Haryana Govt">
            </a>
        </div>
    </div>
    <div class="d-flex flex-column flex-shrink-0 py-3 ps-0">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('adc.sports_kit.dashboard') }}" 
                
                   class="nav-link {{ request()->routeIs('adc.sports_kit.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> <span>Dashboard</span>
                </a>
            </li>
           
            
            <li class="nav-item">
                <a href="{{ route('adc.sports.requests') }}" 
                   class="nav-link {{ request()->routeIs('adc.sports.requests') ? 'active' : '' }}">
                    <i class="fa-regular fa-rectangle-list"></i> <span> Kit Request List</span>
                </a>
            </li>
            <!--<li class="nav-item">
                <a href="{{ route('dso.grad.list') }}" 
                   class="nav-link {{ request()->routeIs('dso.grad.list') ? 'active' : '' }}">
                    <i class="fa-regular fa-rectangle-list"></i> <span>Gradation (C & D)Applications List</span>
                </a>
            </li>-->
        </ul>
    </div>
</aside>
		<!-- SideBar- aside sec Ends -->
        <div class="content-area">
			<header>
				<div class="container-fluid">
					<div class="row justify-content-between border-bottom align-items-center">
						<div class="col-7 logo d-flex">
							<button class="navbar-toggler d-block" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
								<i class="fa-solid fa-angles-left"></i>
							</button>
							<div class="logo_text">
								<h1 class="h1-logo">Sports Department , Government of Haryana</h1>								
							</div>
						</div>
						<div class="col-5 text-end">
							<a href="{{route('login')}}" class="btn btn-danger"><i class="fa-solid fa-power-off"></i> <span>Log Out</span></a>
						</div>
					</div>
				</div>
			</header>