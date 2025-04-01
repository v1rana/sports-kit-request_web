<header>
	<div class="container-fluid">
		<div class="row justify-content-between py-1 border-bottom align-items-center">
			<div class="col-3">
			    <div class="d-flex align-items-center gap-3  py-3 px-4">
                    <img id="logo" src="http://127.0.0.1:8000/assets/job_app/dash/images/logo-sports.png" alt="Sports Haryana Govt" class="img-fluid" style="height: 60px;">
                    <h1 class="fs-4 fw-bold m-0 text-primary text-white">Sports Department</h1>
                </div>
			</div>
			<div class="col-9">
				<nav class="navbar navbar-expand-lg ">
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
						<ul class="navbar-nav">
							<li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
								<a class="nav-link" aria-current="page" href="{{ route('dashboard') }}">Dashboard</a>
							</li>
							<li class="nav-item {{ request()->routeIs('view.applied.certificate') ? 'active' : '' }}">
								<a class="nav-link" href="{{ route('view.applied.certificate') }}">Applied Certificate</a>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									Profile
								</a>
								<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
									<li><a class="dropdown-item" href="#">My Account</a></li>
									<li><a class="dropdown-item" href="#">Change Password</a></li>
									<li><a class="dropdown-item" href="#" onclick="document.getElementById('logout-form').submit();"><i class="dropdown-item"></i> Logout</a>
										<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
											@csrf
										</form>
									</li>
								</ul>
							</li>							
						</ul>
					</div>
				</nav>
			</div>
		</div>
	</div>	
</header>

