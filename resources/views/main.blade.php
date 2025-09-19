@extends('layouts.dashboard')

@section('title', 'Dashborad')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<style>
    .modal-body label{font-size:14px}
</style>

<section>
    <div class="mt-5 text-center">
        <h2>Welcome, <strong class="text-primary">{{ session()->get('user_name') ?? '' }}</strong></h2>
        <p>We are proud of your achievements</p>
    </div>
    <div class="d-flex justify-content-center flex-wrap">
        <a href="{{ route('apply.certificate.form', ['user_id' => session('enUserid')]) }}" class="btn btn-dark p-4 m-2 fw-bold">
            🏆 Apply for Gradation Certificate
        </a>    
        <!-- <a href="{{ route('view.applied.certificate') }}" class="btn btn-success p-4 m-2 fw-bold">
            📜 Download Gradation Certificate
        </a>   -->  
    </div>
</section>

<!-- Bootstrap Table -->
<div class="container mt-4">
    <div class="table-responsive">
        @php $i = 1; @endphp
        @if($otpData->isNotEmpty())
        <table class="table table-striped table-hover bg-white table-bordered text-center" style="margin: auto;">
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
                    <td>{{ $data->appl_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') }}</td>
                    <td>
                       @if($data->certificate_pdf == '' && $data->status == '') 
                            <span class="badge rounded-pill px-3 py-2" style="background: #ffcc00; color: #333; font-weight: 600;">
                                ⏳ In-Progress.....
                            </span>
                        @elseif($data->certificate_pdf == '' && $data->status == 'Rejected')
                            <span class="badge rounded-pill px-3 py-2" style="background: #dc3545; color: #fff; font-weight: 600;">
                                <i class="fa fa-times" aria-hidden="true"></i> Rejected
                            </span>
                        @elseif($data->certificate_pdf == '' && $data->status == 'Approved')
                            <span class="badge rounded-pill px-3 py-2" style="background: #28a745; color: #fff; font-weight: 600;">
                                <i class="fa fa-check" aria-hidden="true"></i> Approved
                            </span>
                        @elseif($data->certificate_pdf != '' && $data->status == 'Approved')
                            <span class="badge rounded-pill px-3 py-2" style="background: #007bff; color: #fff; font-weight: 600;">
                                🎓 Released
                            </span>
                            <br>
                            <div style="background: #fff8d1; border-left: 6px solid #ffcc00; padding: 2px 0px; border-radius: 8px; font-weight: 500; margin-top: 15px;">
                                📌 <strong>Note:</strong> Applicant can collect their certificate from the <strong>DS Office</strong>.
                            </div>
                        @else
                            <span class="badge rounded-pill px-3 py-2" style="background: #6c757d; color: #fff; font-weight: 600;">
                                ❔ Unknown
                            </span>
                        @endif
                    </td>
                    <td>
					    <button style="width:100px" class="btn btn-info btn-sm" 
							onclick="viewDetails(
								'{{ url('storage/' . ($data->profile_picture ?? 'default.jpg')) }}',
								'{{ $data->sports_person_name }}', 
								'{{ $data->aadhaar_no }}',
								'{{ $data->mobile_no }}', 
								'{{ $data->district_sportsperson_belongs }}', 
								'{{ $data->domicile_state }}', 
								'{{ $data->plays_for_statte_org }}',
								'{{ $data->name_sports_discipline }}', 
								'{{ $data->tournament }}', 
								'{{ \Carbon\Carbon::parse($data->month_year)->format('d-m-Y') }}', 
								'{{ $data->venue_of_tournament }}',
								'{{ $data->organising_authority }}', 
								'{{ $data->tournament_type }}', 
								'{{ $data->medal_won }}', 
								'{{ $data->participation_level }}',
								'{{ url('storage/' . ($data->aadhaar_card ?? 'default.jpg')) }}', 
								'{{ url('storage/' . ($data->domicile_certificate ?? 'default.jpg')) }}', 
								'{{ url('storage/' . ($data->sports_certificate ?? 'default.jpg')) }}', 
								'{{ url('storage/' . ($data->more_than25_photo ?? 'default.jpg')) }}',
                                '{{ url('storage/' . ($data->noc_upload ?? 'default.jpg')) }}',
                                '{{ url('storage/' . ($data->date_ofbirth_certificate ?? 'default.jpg')) }}',
                                '{{ url('storage/' . ($data->verif_fron_conc_auth ?? 'default.jpg')) }}',
                                '{{ url('storage/' . ($data->affidavit_uplod ?? 'default.jpg')) }}',
                                '{{ url('storage/' . ($data->coach_certif ?? 'default.jpg')) }}'
							)">
							👁️ View Details
						</button>
                        @if(empty($data->verification_by_sportsperson) || empty($data->verify_status))
                        <a style="width:146px" href="{{ route('verification.by.sportsperson', ['id' => $data->id]) }}" class="btn btn-warning">Verify Your Application</a>
                        @endif
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
<div class="modal fade show applicant-details-modal" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow-lg rounded-4 border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold text-white" id="detailsModalLabel">Sports Person Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <!-- Profile Photo -->
                    <div class="card mb-4"><div class="card-body bg-light">
                    <div class="row">
                        <div class="col-10 mb-3">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <label class="text-muted fw-bold">1. NAME OF SPORTSPERSON</label>
                                    <h5 class="" id="modalName"></h5>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                <label class="text-muted fw-bold">2. AADHAAR NO.</label>
                                    <h5 id="adhar_no"></h5>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                <label class="text-muted fw-bold">3. MOBILE NO.</label>
                                    <h5 id="modalPhone"></h5>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <label class="text-muted fw-bold">4. DISTRICT</label>
                                    <h5 id="belongTo"></h5>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <label class="text-muted fw-bold">5. DOMICILE STATE</label>
                                    <h5 id="domiState"></h5>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                                    <label class="text-muted fw-bold">6. PLAYS FOR </label>
                                    <h5 id="organisation"></h5>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 mb-3">
                                     <label class="text-muted fw-bold">7. NAME OF SPORTS DISCIPLINE</label>
                                    <h5 id="sport_displ"></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-2 mb-3 text-end">
                            <img id="modalProfilePic" src="" class="  border shadow" height="100%" width="100%" alt="Profile Photo">
                        </div>
                    </div></div></div>

                    <!-- Sports Details -->
                    <h5 class="fw-bold mb-3">Best Sports Achievement</h5>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="text-muted fw-bold">I. NAME OF TOURNAMENT</label>
                            <h5 class="fw-semibold" id="nameOfTounmnt"></h5>
                        </div>
                        <div class="col-md-9 mb-3">
                            <label class="text-muted fw-bold">II. ORGANIZING AUTHORITY</label>
                            <h5 class="fw-semibold" id="ornAthority"></h5>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted fw-bold">III. DATE</label>
                            <h5 class="fw-semibold" id="month_year"></h5>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted fw-bold">IV. VENUE OF TOURNAMENT</label>
                            <h5 class="fw-semibold" id="vanueOfTournam"></h5>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted fw-bold">V. TOURNAMENT TYPE</label>
                            <h5 class="fw-semibold" id="tounType"></h5>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted fw-bold">VI. MEDAL WON </label>
                            <h5 class="fw-semibold" id="modalMedal"></h5>
                        </div>
						<div class="col-md-3 mb-3">
                            <label class="text-muted fw-bold">VII. PARTICIPATION LEVEL</label>
                            <h5 class="fw-semibold" id="patiLevel"></h5>
                        </div>
                    </div>
                    <hr />

                    <!-- Attachments -->
                    <h5 class="fw-bold mb-3">Attachments</h5>
                    <div class="row">
                        <div class="col-3 mb-3">
                            <div class="card"><div class="card-body p-0 text-center">
                            <p class="text-muted p-3 mb-0">1. AADHAAR CARD (PDF/JPG)</p><a id="modalAadhaar" class="btn btn-success w-100" href="#" target="_blank">View</a>
                            </div>
                            </div>
                        </div>
                        
                        
                        <div class="col-3 mb-3">
                            <div class="card"><div class="card-body p-0 text-center">
                            <p class="text-muted p-3 mb-0">2. DOMICILE UPLOAD (PDF/JPG)</p> <a id="modalDomicile" class="btn btn-success w-100" href="#" target="_blank">View</a>
                            </div>
                            </div>
                        </div>
                        <div class="col-3 mb-3">
                            <div class="card"><div class="card-body p-0 text-center">
                            <p class="text-muted p-3 mb-0">3. ACHIEVMENT CERTIFICATE UPLOAD (PDF/JPG) </p><a id="modalSportsCert" class="btn btn-success w-100" href="#" target="_blank">View</a>
                            </div>
                            </div>
                        </div>
                        <div class="col-3 mb-3">
                            <div class="card"><div class="card-body p-0  text-center">
                            <p class="text-muted p-3 mb-0">4. Add NOC Upload (for Certifying Played from Other State/UT/Organisation)</p><a id="noc_upload" class="btn btn-success w-100" href="#" target="_blank">View</a>
                            </div>
                            </div>
                        </div>
                        <div class="col-3 mb-3">
                            <div class="card"><div class="card-body p-0  text-center">
                            <p class="text-muted p-3 mb-0">5. CERTIFICATE FOR AS PROOF FOR PLAYING MORE THAN 25% OF MATCHES. (PDF/JPG)</p><a id="more_than25_photo" class="btn btn-success w-100" href="#" target="_blank">View</a>
                            </div>
                            </div>
                        </div>
                    </div>
                    <hr />

                    
                    <div class="row">
                        <div class="col-3 mb-3">
                            <div class="card"><div class="card-body p-0 text-center">
                            <p class="text-muted p-3 mb-0" style="padding: 3.5rem !important;">Date of Birth Certificate</p><a id="date_ofbirth_certificate" class="btn btn-success w-100" href="#" target="_blank">View</a>
                            </div>
                            </div>
                        </div>
                        
                        
                        <div class="col-3 mb-3">
                            <div class="card"><div class="card-body p-0 text-center">
                            <p class="text-muted p-3 mb-0">Verification from Concerned Authority</p> <a id="verif_fron_conc_auth" class="btn btn-success w-100" href="#" target="_blank">View</a>
                            </div>
                            </div>
                        </div>
                        <div class="col-3 mb-3">
                            <div class="card"><div class="card-body p-0 text-center">
                            <p class="text-muted p-3 mb-0" style="padding: 4.5rem !important;">Affidavit Upload</p><a id="affidavit_uplod" class="btn btn-success w-100" href="#" target="_blank">View</a>
                            </div>
                            </div>
                        </div>
                        <div class="col-3 mb-3">
                            <div class="card"><div class="card-body p-0  text-center">
                            <p class="text-muted p-3 mb-0" style="padding: 4.5rem !important;">Coach Certificate</p><a id="coach_certif" class="btn btn-success w-100" href="#" target="_blank">View</a>
                            </div>
                            </div>
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