<style>
  
</style>
<!-- SideBar- aside sec -->
<aside class="collapse show" id="navbarToggleExternalContent">
    <div class="d-flex align-items-center justify-content-center text-white logo-area">
        <div class="logo">
            <a href="#" title="Go to home" class="site_logo" rel="home">
                <img class="" id="logo" src="{{ url('assets/images/logo-sports.png') }}" alt="Sports Haryana Govt">
            </a>
        </div>
    </div>
    <div class="d-flex flex-column flex-shrink-0 py-3 ps-0">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
        <a href="{{ route('dso.sports_kit.dashboard') }}" 
           class="nav-link {{ request()->routeIs('dso.sports_kit.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i> <span>Dashboard</span>
        </a>
    </li>
           
            
           {{-- Equipment menu --}}
    <li class="nav-item menu-item" data-tab="equipments">
        <a href="{{ route('dso.sports.requests') }}" 
           class="nav-link {{ request()->routeIs('dso.sports.requests') ? 'active' : '' }}">
            <i class="fa-regular fa-rectangle-list"></i> <span>Kit Request/In-Progress List</span>
        </a>
    </li>
	<li class="nav-item menu-item" data-tab="equipments">
        <a href="{{ route('dso.sports.verified') }}" 
           class="nav-link {{ request()->routeIs('dso.sports.verified') ? 'active' : '' }}">
            <i class="fa-solid fa-circle-check"></i> <span>Kit Verified List</span>
        </a>
    </li>
	<li class="nav-item menu-item" data-tab="equipments">
        <a href="{{ route('dso.sports.disbursed') }}" 
           class="nav-link {{ request()->routeIs('dso.sports.disbursed') ? 'active' : '' }}">
            <i class="fa-solid fa-box-open"></i> <span>Kit Disbursed List</span>
        </a>
    </li>
            {{-- Gradation menu --}}
    <li class="nav-item menu-item" data-tab="gradations">
        <a href="{{ route('dso.grad.list') }}" 
           class="nav-link {{ request()->routeIs('dso.grad.list') ? 'active' : '' }}">
           <i class="fa-solid fa-address-card"></i> <span>Gradation (C & D) Applications List</span>
        </a>
    </li>
	<li class="nav-item menu-item" data-tab="gradations">
        <a href="{{ route('dso.approved_grad.list') }}" 
           class="nav-link {{ request()->routeIs('dso.approved_grad.list') ? 'active' : '' }}">
           <i class="fa-solid fa-address-card"></i> <span>Approved List</span>
        </a>
    </li>
	<li class="nav-item menu-item" data-tab="gradations">
        <a href="{{ route('dso.rejected_grad.list') }}" 
           class="nav-link {{ request()->routeIs('dso.rejected_grad.list') ? 'active' : '' }}">
           <i class="fa-solid fa-address-card"></i> <span>Rejected List</span>
        </a>
    </li>
	<li class="nav-item menu-item" data-tab="gradations">
        <a href="{{ route('dso.certificate_issued_grad.list') }}" 
           class="nav-link {{ request()->routeIs('dso.certificate_issued_grad.list') ? 'active' : '' }}">
           <i class="fa-solid fa-address-card"></i> <span>Certificate Issued List</span>
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
								<i class="fa-solid fa-bars"></i>
							</button>
							<div class="logo_text">
    <h1 class="h1-logo">Sports Department , Government of Haryana</h1>
    @if (!empty(session('district')))
        <div class="text-white mt-1">
            ( {{ session('district') }} )
        </div>
    @endif
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
    const tabButtons = document.querySelectorAll('#myTab .nav-link');
    const tabContent = document.querySelectorAll('.tab-pane');
    const menuItems = document.querySelectorAll('.menu-item');

    // Step 1: Read saved tab from localStorage or default
    const savedTab = localStorage.getItem('selectedTab') || 'equipments';

    function activateTab(tabName) {
        tabButtons.forEach(btn => {
            const tab = btn.getAttribute('data-tab');
            const paneId = btn.getAttribute('data-bs-target');
            const isActive = tab === tabName;

            btn.classList.toggle('active', isActive);
            const pane = document.querySelector(paneId);
            if (pane) {
                pane.classList.toggle('show', isActive);
                pane.classList.toggle('active', isActive);
            }
        });

        // Update sidebar menu visibility
        menuItems.forEach(item => {
            const tabKey = item.getAttribute('data-tab');
            item.style.display = (tabKey === tabName) ? 'block' : 'none';
        });
    }

    // Step 2: On load - activate saved tab and update sidebar
    activateTab(savedTab);

    // Step 3: When user clicks tab
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const selected = this.getAttribute('data-tab');
            localStorage.setItem('selectedTab', selected);
            activateTab(selected);
        });
    });
});
</script>


