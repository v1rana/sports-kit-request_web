@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">🏆 Dashboard</h2>

    <div class="row">
        <!-- Total Applications Card -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">📋 Total Applications</div>
                <div class="card-body">
                    <h4 class="card-title">{{ $totalApplications ?? 0 }}</h4>
                </div>
            </div>
        </div>

        <!-- Approved Applications Card -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">✅ Approved Applications</div>
                <div class="card-body">
                    <h4 class="card-title">{{ $approvedApplications ?? 0 }}</h4>
                </div>
            </div>
        </div>

        <!-- Pending Applications Card -->
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">⏳ Pending Applications</div>
                <div class="card-body">
                    <h4 class="card-title">{{ $pendingApplications ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Applications Table -->
    <div class="card mt-4">
        <div class="card-header bg-dark text-white">📑 Latest Applications</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>District</th>
                        <th>Block</th>
                        <th>Area</th>
                        <th>Designation</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                   
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
