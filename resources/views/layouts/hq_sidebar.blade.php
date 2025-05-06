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
                <a href="{{ route('hq.sports_kit.dashboard') }}" 
                
                   class="nav-link {{ request()->routeIs('hq.sports_kit.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('hq.vendor-list') }}" 
                   class="nav-link {{ request()->routeIs('hq.vendor-list') ? 'active' : '' }}">
                    <i class="fa-regular fa-rectangle-list"></i> <span> Vendor</span>
                </a>
            </li>
                 {{-- Equipment menu --}}
    <li class="nav-item menu-item" data-tab="equipments">
        <a href="{{ route('hq.sports.requests') }}" 
           class="nav-link {{ request()->routeIs('hq.sports.requests') ? 'active' : '' }}" data-tab="equipments">
            <i class="fa-regular fa-rectangle-list"></i> <span>Kit Request List</span>
        </a>
    </li>
			
			
			{{-- Gradation menu --}}
    <li class="nav-item menu-item" data-tab="gradations">
        <a href="" 
           class="nav-link" data-tab="gradations">
           <i class="fa-solid fa-address-card"></i> <span>Gradation (A & B) Applications List</span>
        </a>
    </li>
	{{-- OSP menu --}}
			<li class="nav-item menu-item" data-tab="jobs">
                <a href="{{ route('hq.hosp.requests') }}" 
                   class="nav-link {{ request()->routeIs('hq.hosp.requests') ? 'active' : '' }}"  data-tab="jobs">
                    <i class="fa-regular fa-rectangle-list"></i> <span> HOSP Request List</span>
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
							<a href="{{route('login')}}" class="btn btn-danger"><i class="fa-solid fa-power-off"></i> <span>Log Out</span></a>
						</div>
					</div>
				</div>
			</header>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.dashboard-stats .nav-link');
    const menuItems = document.querySelectorAll('.menu-item');

    // Save selected tab in localStorage and update sidebar
   tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const selected = this.textContent.trim().toLowerCase(); // "equipments", "gradations", "jobs"
            localStorage.setItem('selectedTab', selected);
            updateSidebar(selected);
        });
    });

    // Show only matched menu items in sidebar
    function updateSidebar(tab) {
        menuItems.forEach(item => {
            const tabKey = item.getAttribute('data-tab');
            item.style.display = (tabKey === tab) ? 'block' : 'none';
        });
    }

    // On page load
    const allowedTabs = ['equipments', 'jobs', 'gradations'];
    const savedTab = localStorage.getItem('selectedTab');
    updateSidebar(allowedTabs.includes(savedTab) ? savedTab : 'equipments');
});
</script>
			