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
                    <th>Gender</th>
                    <th>State</th>
                    <th>Sports Discipline</th>
                    <th>Tournament Name</th>
                    <th>Month/Year</th>
                    <th>Venue</th>
                    <th>Organising Authority</th>
                    <th>Tournament Type</th>
                    <th>Medal Won</th>
                    <th>Participation Level</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sportsCertificates as $index => $certificate)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $certificate->certificate_no }}</td>
                    <td>{{ $certificate->sports_person_name }}</td>
                    <td>{{ $certificate->gender }}</td>
                    <td>{{ $certificate->domicile_state }}</td>
                    <td>{{ $certificate->name_sports_discipline }}</td>
                    <td>{{ $certificate->tournament }}</td>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $certificate->month_year)->format('F Y') }}</td>
                    <td>{{ $certificate->venue_of_tournament }}</td>
                    <td>{{ $certificate->authority }}</td>
                    <td>{{ $certificate->tournament_type }}</td>
                    <td>{{ $certificate->medal_won }}</td>
                    <td>{{ $certificate->participation_level }}</td>
                    <td>
                        <strong>
                            @if($certificate->status == 'Approved')
                                <span class="badge rounded-pill bg-success"><i class="fa-solid fa-thumbs-up"></i> Approved</span>
                            @elseif($certificate->status == 'Rejected')
                                <span class="badge rounded-pill bg-danger"><i class="fa-solid fa-ban"></i>  Rejected</span>
                            @else
                                <span class="badge rounded-pill bg-info"><i class="fa-solid fa-hourglass-half"></i> Pending</span>
                            @endif
                        </strong>
                @if($certificate->status == 'Pending')
                    <form action="{{ route('dso.approve', $certificate->id) }}" method="POST" style="display:inline;">
                        @csrf
						<button type="submit"  class="btn btn-success w-100 mb-2">
                     Approved
                </button>
                    </form>

                    <form action="{{ route('dso.reject', $certificate->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">
                            Rejected
                        </button>
                    </form>
                @endif
            </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
