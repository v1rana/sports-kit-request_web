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
                <a href="{{ route('sports_kit.dashboard') }}" 
                
                   class="nav-link {{ request()->routeIs('sports_kit.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('sports_kit.form') }}" 
                   class="nav-link {{ request()->routeIs('sports_kit.form') ? 'active' : '' }}">
                   <i class="fa-solid fa-file-lines"></i> <span> Kit Requisition Form</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('sports.requests') }}" 
                   class="nav-link {{ request()->routeIs('sports.requests') ? 'active' : '' }}">
                    <i class="fa-regular fa-rectangle-list"></i> <span> Kit Request List</span>
                </a>
            </li>
           
        </ul>
    </div>
</aside>
		<!-- SideBar- aside sec Ends -->
        <div class="content-area">
			<header>
				<div class="container-fluid">
					<div class="row justify-content-between border-bottom align-items-center">
						<div class="col-6 logo d-flex">
							<button class="navbar-toggler d-block" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
								<i class="fa-solid fa-angles-left"></i>
							</button>
							<div class="logo_text">
								<h1 class="h1-logo">Sports Department , Government of Haryana</h1>								
							</div>
						</div>
						<div class="col-6 text-end">
							<a href="{{route('sk.logout')}}" class="btn btn-danger"><i class="fa-solid fa-power-off"></i> <span>Log Out</span></a>
						</div>
					</div>
				</div>
			</header>