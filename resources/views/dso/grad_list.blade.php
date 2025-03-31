@extends('layouts.dso_main')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<h4 class="mb-4">Sports Gradation Certificates List 
    <a href="{{ url()->previous() }}" class="btn btn-secondary float-end">
        <i class="fa-solid fa-arrow-left-long"></i> Back
    </a>
</h4>

<div class="bg-white shadow mb-5 p-3">
    <div class="table-responsive">
        <table class="table table-bordered bg-white table-hover">
            <thead>
                <tr class="bg-primary text-white">
                    <th>Sr. No.</th>
                    <th>Certificate No</th>
                    <th>Sports Person Name</th>
                    <th>Aadhaar No</th>
                    <th>Email</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Mobile No</th>
                    <th>District</th>
                    <th>State</th>
                    <th>Sports Discipline</th>
                    <th>Tournament Name</th>
                    <th>Month/Year</th>
                    <th>Venue</th>
                    <th>Organising Authority</th>
                    <th>Tournament Type</th>
                    <th>Medal Won</th>
                    <th>Participation Level</th>
                    <th>Granted Grade</th>
                    <th>Profile Picture</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sportsCertificates as $index => $certificate)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $certificate->certificate_no }}</td>
                    <td>{{ $certificate->sports_person_name }}</td>
                    <td>{{ $certificate->aadhaar_no }}</td>
                    <td>{{ $certificate->email }}</td>
                    <td>{{ date('d-m-Y', strtotime($certificate->dob)) }}</td>
                    <td>{{ $certificate->gender }}</td>
                    <td>{{ $certificate->mobile_no }}</td>
                    <td>{{ $certificate->district_sportsperson_belongs }}</td>
                    <td>{{ $certificate->domicile_state }}</td>
                    <td>{{ $certificate->name_sports_discipline }}</td>
                    <td>{{ $certificate->tournament_name }}</td>
                    <td>{{ $certificate->month_year }}</td>
                    <td>{{ $certificate->venue_of_tournament }}</td>
                    <td>{{ $certificate->organising_authority }}</td>
                    <td>{{ $certificate->tournament_type }}</td>
                    <td>{{ $certificate->medal_won }}</td>
                    <td>{{ $certificate->participation_level }}</td>
                    <td>{{ $certificate->granted_grade }}</td>
                    <td>
                        @if($certificate->profile_picture)
                            <img src="{{ asset('storage/' . $certificate->profile_picture) }}" alt="Profile" class="img-thumbnail" width="50">
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        <a href="" class="btn btn-primary btn-sm">View</a>
                        <a href="" class="btn btn-warning btn-sm">Edit</a>
                        <form action="" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
