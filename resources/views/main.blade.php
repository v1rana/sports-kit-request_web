@extends('layouts.dashboard')

@section('title', 'Sports !! Dashborad')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<section>
    <div class="mt-5 text-center">
        <h2>Welcome, <strong class="text-primary">{{ session()->get('sports_person_name')  ?? '' }}</strong></h2>
        <p>We are proud of your achievements</p>
    </div>
    <div class="d-flex justify-content-center flex-wrap">
        <a href="{{ route('apply.certificate.form') }}" class="btn btn-dark p-4 m-2 fw-bold">
            🏆 Apply for Gradation Certificate
        </a>    
        <a href="{{ route('view.applied.certificate') }}" class="btn btn-success p-4 m-2 fw-bold">
            📜 Download Gradation Certificate
        </a>    
    </div>
</section>

<!-- Bootstrap Table -->
<div class="container mt-4">
    <div class="table-responsive">
        @php $i = 1; @endphp
        @if($otpData->isNotEmpty())
        <table class="table table-striped table-hover table-bordered text-center" style="margin: auto;">
            <thead class="table-dark">
                <tr>
                    <th>Sr. No.</th>
                    <th>Application Id</th>
                    <th>Applied Date</th>
                    <th>Status</th>                    
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($otpData as $data)
                <tr>
                    <td>{{ $i }}</td>  <!-- Corrected variable -->
                    <td>{{ $data->certificate_no }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('Y-m-d') }}</td>
                    <td>
                        <span class="badge rounded-pill px-3 py-2" style="background: #ffcc00; color: #333; font-weight: 600;">
                            ⏳ Pending.....
                        </span>
                    </td>
                    <td>
					    <button class="btn btn-info btn-sm" 
							onclick="viewDetails(
								'{{ url('storage/' . ($data->profile_picture ?? 'default.jpg')) }}',
								'{{ $data->sports_person_name }}', 
								'{{ $data->aadhaar_no }}',
								'{{ $data->mobile_no }}', 
								'{{ $data->district_sportsperson_belongs }}', 
								'{{ $data->domicile_state }}', 
								'{{ $data->plays_for_statte_org }}',
								'{{ $data->name_sports_discipline }}', 
								'{{ $data->tournament_name }}', 
								'{{ $data->month_year }}', 
								'{{ $data->venue_of_tournament }}',
								'{{ $data->organising_authority }}', 
								'{{ $data->tournament_type }}', 
								'{{ $data->medal_won }}', 
								'{{ $data->participation_level }}',
								'{{ url('storage/' . ($data->aadhaar_card ?? 'default.jpg')) }}', 
								'{{ url('storage/' . ($data->domicile_certificate ?? 'default.jpg')) }}', 
								'{{ url('storage/' . ($data->sports_certificate ?? 'default.jpg')) }}', 
								'{{ url('storage/' . ($data->more_than25_photo ?? 'default.jpg')) }}'
							)">
							👁️ View
						</button>
                    </td>
                </tr>
                @php $i++; @endphp  <!-- Increment $i here -->
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-center text-muted mt-3">No data available</p>
        @endif
    </div>
</div>


<!-- Bootstrap Modal -->
<!-- Bootstrap Modal -->
<div class="modal fade show" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow-lg rounded-4 border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="detailsModalLabel">Sports Person Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="container">
                    <!-- Profile Photo -->
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <img id="modalProfilePic" src="" class="rounded-circle border shadow" width="120" height="120" alt="Profile Photo">
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">1. NAME OF SPORTSPERSON</h6>
                            <p class="fw-semibold" id="modalName"></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">2. AADHAAR NO.</h6>
                            <p class="fw-semibold" id="adhar_no"></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">3. MOBILE NO.</h6>
                            <p class="fw-semibold" id="modalPhone"></p>
                        </div>
                    </div>
					<div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">4. NAME OF DISTRICT SPORTSPERSON BELONGS TO</h6>
                            <p class="fw-semibold" id="belongTo"></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">5. DOMICILE STATE</h6>
                            <p class="fw-semibold" id="domiState"></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">6. PLAYS FOR (NAME OF STATE/ORGANIZATION)</h6>
                            <p class="fw-semibold" id="organisation"></p>
                        </div>
                    </div>
					<div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">7. NAME OF SPORTS DISCIPLINE</h6>
                            <p class="fw-semibold" id="sport_displ"></p>
                        </div>
                    </div>

                    <!-- Sports Details -->
                    <h5 class="fw-bold mb-3">Best Sports Achievement</h5>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">I. NAME OF TOURNAMENT</h6>
                            <p class="fw-semibold" id="nameOfTounmnt"></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">II. MONTH & YEAR</h6>
                            <p class="fw-semibold" id="month_year"></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">III. VENUE OF TOURNAMENT</h6>
                            <p class="fw-semibold" id="vanueOfTournam"></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">IV. ORGANIZING AUTHORITY</h6>
                            <p class="fw-semibold" id="ornAthority"></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">V. TOURNAMENT TYPE</h6>
                            <p class="fw-semibold" id="tounType"></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">VI. MEDAL WON (IF ANY) </h6>
                            <p class="fw-semibold" id="modalMedal"></p>
                        </div>
						<div class="col-md-4 mb-3">
                            <h6 class="text-muted">VII. PARTICIPATION LEVEL (IN CASE OF TEAM GAME ONLY) </h6>
                            <p class="fw-semibold" id="patiLevel"></p>
                        </div>
                    </div>

                    <!-- Attachments -->
                    <h5 class="fw-bold mb-3">Attachments</h5>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">AADHAAR CARD (PDF/JPG)</h6>
                            <a id="modalAadhaar" href="#" target="_blank">View</a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">DOMICILE UPLOAD (PDF/JPG)</h6>
                            <a id="modalDomicile" href="#" target="_blank">View</a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">ACHIEVMENT CERTIFICATE UPLOAD (PDF/JPG)</h6>
                            <a id="modalSportsCert" href="#" target="_blank">View</a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">CERTIFICATE FOR AS PROOF FOR PLAYING MORE THAN 25% OF MATCHES. (PDF/JPG)</h6>
                            <a id="more_than25_photo" href="#" target="_blank">View</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



@endsection