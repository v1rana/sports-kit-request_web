@extends('gm_main')

@section('content')

    <!-- Main Content -->
    <div class="content">
        
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm rounded">
            <div class="container-fluid">
                <span class="navbar-brand">Dashboard</span>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>

        <!-- Dashboard Cards -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card p-3 text-center bg-primary text-white">
                    <h5 class="text-white">Total Applications</h5>
                    <h3 class="text-white">{{ $totalApplications }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 text-center bg-info text-dark">
                    <h5 class="text-white">Total Pending</h5>
                    <h3 class="text-white">{{ $totalPending }}</h3>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card p-3 text-center bg-success text-white">
                    <h5 class="text-white">Total Approved</h5>
                    <h3 class="text-white">{{ $totalApproved }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 text-center bg-warning text-dark">
                    <h5 class="text-white">Total Rejection</h5>
                    <h3 class="text-white">{{ $totalRejected }}</h3>
                </div>
            </div>
        </div>

        <!-- Table -->
        
        </div>
    </div>
    @endsection