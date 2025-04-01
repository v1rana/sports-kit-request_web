@extends('adc_main')

@section('content')

    <!-- Main Content -->
    <div class="content dashboard-area">
        
         <!-- Top Navbar -->
         <div class="row header-area align-items-end">
            <div class="col-7">
                <h3 class="mb-0 text-dark">Welcome, <strong>ADC USER</strong></h3>
            </div>
            <div class="col-5 text-end">
                <h6 class="mb-0"><i class="fa-solid fa-location-dot"></i> Bhiwani</h6>
            </div>
        </div>
        <hr />
        <!-- Dashboard Cards -->
        <div class="row mt-4">
            <div class="col-4">
                <div class="d-flex rounded justify-content-center flex-column p-3 text-center bg-primary text-white" style="height:220px;">
                    <h2 class="text-white">{{ $totalApplications }}</h2>
                    <small class="text-white">Total Applications</small>
                    <i class="fa-solid fa-list"></i>
                </div>
            </div>
            <div class="col-8">
                <div class="row application-details">
                    
                    <div class="col-md-4">
                        <div class="p-3 rounded text-center bg-success shadow text-white mb-3">
                            <h2 class="text-white">{{ $totalApproved }}</h2>
                            <small class="text-white">Approved</small>
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded text-center bg-danger shadow text-white mb-3">
                            <h2 class="text-white">{{ $totalRejected }}</h2>
                            <small class="text-white">Rejected</small>
                            <i class="fa-solid fa-ban"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded text-center bg-dispersment  shadow text-white mb-3">
                            <h2 class="text-white">{{ $totalDisbursed }}</h2>
                            <small class="text-white">Total Disbursement</small>
                            <i class="fa-solid fa-table"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        
        </div>
    </div>
    @endsection