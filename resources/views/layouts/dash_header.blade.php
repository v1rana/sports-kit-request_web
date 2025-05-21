<header>
<div class="container-fluid bg-primary text-white py-3">
    <div class="row align-items-center justify-content-between px-4">
        <!-- Logo and Title Section -->
        <div class="col-md-6 d-flex align-items-center gap-3">
            <img id="logo" src="{{ url('assets/job_app/dash/images/logo-sports.png') }}" alt="Sports Haryana Govt" class="img-fluid" style="height: 60px;">
            <div>
                <h4 class="m-0 fw-bold text-white">Sports Department, Haryana</h4>
                <p class="m-0 small">Sports Gradation Certificate - Player Login</p>
            </div>
        </div>

       @if (!request()->is('dso/certificates/download-pdf'))
    <!-- Menu Section -->
    <div class="col-md-6 text-end">
        <a href="{{ route('dashboard') }}" class="text-white me-3">Dashboard</a>

        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
@endif

    </div>
</div>

</header>

