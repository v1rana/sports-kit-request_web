@extends('hq_main')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <style>
        label.info-label,
        small.info-label {
            font-weight: 500;
            font-size: 14px;
            display: block;
            margin-bottom: 0;
            line-height: normal
        }

        .modal-body h5 {
            margin: 0
        }

        .games-authorised-sec .row>div {
            font-size: 15px;
            padding: 0
        }

        .games-authorised-sec .row>div h6 {
            margin: 0;
            padding: 6px 10px;
            border-left: 1px solid rgba(0, 0, 0, 0.07);
            background: #eee;
            color: #777;
        }

        .games-authorised-sec .row>div:first-child h6 {
            border: none;
        }

        .games-authorised-sec .row>div:nth-child(-n+4) {
            border-top: 0;
        }

        .games-authorised-sec .row>div p {
            padding: 5px 10px;
            font-size: 14px;
        }

        .app-id-view-btn {
            border-width: 0 0 1px;
            text-align: left;
            border-style: dotted;
            width: auto;
            margin-bottom: 2px;
            white-space: nowrap;
            border-color: blue;
            font-weight: bold;
        }

        .modal label {
            font-size: 15px;
            margin-bottom: 0;
            color: #6c757d !important;
        }

        .modal-title-details {
            background: rgba(0, 0, 0, 0.04);
            padding: 10px;
            margin: 0;
            color: #36454F;
            font-size: 20px;
            text-transform: uppercase;
        }

        .education-area span {
            font-size: 12px;
            color: #00f;
            float: left;
            clear: both;
            margin-left: 20px;
            font-weight: normal;
            border-bottom: 1px dotted;
        }
    </style>
    <h4 class="">Haryana Outstanding Sports Persons List <a href="" class="btn btn-secondary float-end"><i
                class="fa-solid fa-arrow-left-long"></i> Back</a></h4>
    <div class=" bg-white shadow mb-5 p-2 w-100 table-responsive">
        <table class="table table-bordered bg-white table-hover">
            <thead>
                <tr class="bg-primary text-white">
                    <th>Sr. No.</th>
                    <th>Application Id</th>
                    <th>Name</th>
                    <th>District</th>
                    <!--th>Block</th>
                        <th>Ward/Village</th-->
                    <th>Event Type</th>
                    <th>Tournament</th>
                    <th>Organizing Committee</th>
                    <!--th>Physical Disablity</th>
                        <th>Level of Tournament</th>
                        <th>Game</th>
                        <th>venue</th>
                        <th>Medal</th>
                        <th>Achievement Date</th-->
                    <!--th>Participation level</th-->
                    <th>Application status</th>
                    <!--th>Action</th-->
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
				@if ($user->userDetails)
					{{-- @php $request = $user->userDetails; @endphp --}}
					@endif
                    @foreach ($user->userDetails as $index => $application)
                        <tr>
                            <td>{{ $index + 1 }}.</td>
                            <td><button type="button" class="bg-transparent text-primary app-id-view-btn"
                                    data-bs-toggle="modal" data-bs-target="#modal{{ $application->declarationsHosp[0]->id }}">
                                    {{ $application->application_id ?? 'N/A' }}
								</button>
                                <div class="modal fade" id="modal{{ $application->declarationsHosp[0]->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header bg-white">
                                                <h5 class="modal-title" id="exampleModalLabel">Application ID -
                                                    {{ $application->application_id ?? 'N/A' }}
												</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-sm-6 col-md-3">
                                                        <label>Application Submitted Date</label>
                                                        <h6>{{ \Carbon\Carbon::parse($application->created_at)->format('d M Y') }}
                                                        </h6>
                                                    </div>
                                                    <div class="col-sm-12 col-sm-6 col-md-3">
                                                        <label>Application Status</label>
                                                        @php
                                                            $status = $application->status;
                                                            $statusText = 'In-Progress';
                                                            $badgeClass = 'bg-warning';
                                                            $icon = '<i class="fa-solid fa-hourglass-half"></i>';

                                                            if ($status == '1') {
                                                                $statusText = 'Approved';
                                                                $badgeClass = 'bg-success';
                                                                $icon = '<i class="fa-solid fa-thumbs-up"></i>';
                                                            } elseif ($status == '2') {
                                                                $statusText = 'Rejected';
                                                                $badgeClass = 'bg-danger';
                                                                $icon = '<i class="fa-solid fa-ban"></i>';
                                                            }
                                                        @endphp


                                                        <h6><strong>
                                                                @if ($application->status == '1')
                                                                    <span class="badge bg-success"><i
                                                                            class="fa-solid fa-thumbs-up"></i>
                                                                        Approved</span>
                                                                @elseif($application->status == '2')
                                                                    <span class="badge bg-danger"><i
                                                                            class="fa-solid fa-ban"></i> Rejected</span>
                                                                @else
                                                                    <form
                                                                        action="{{ route('hq.approveReject', $application->id) }}"
                                                                        method="POST" style="display:inline;"
                                                                        onsubmit="return confirm('Are you sure you want to Approve this?');">
                                                                        @csrf
                                                                        <input type="text" value="1" name="status"
                                                                            hidden>
                                                                        <button type="submit"
                                                                            class="btn btn-success btn-sm mb-1">Approve</button>
                                                                    </form>
                                                                    <form
                                                                        action="{{ route('hq.approveReject', $application->id) }}"
                                                                        method="POST" style="display:inline;"
                                                                        id="rejectForm-{{ $application->id }}">
                                                                        @csrf
                                                                        <input type="hidden" name="status" value="2">

                                                                        {{-- Remarks textarea + Submit Rejection button (initially hidden) --}}
                                                                        <div id="remarksBox-{{ $application->id }}"
                                                                            style="display:none; margin-top: 10px;">
                                                                            <textarea name="remarks" class="form-control mb-2" rows="3" placeholder="Enter remarks (required)"></textarea>
                                                                            <button type="submit"
                                                                                class="btn btn-danger btn-sm">Submit
                                                                                Rejection</button>
                                                                        </div>

                                                                        {{-- Initial Reject button --}}
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm mb-1"
                                                                            id="rejectBtn-{{ $application->id }}"
                                                                            onclick="showRemarks({{ $application->id }})">
                                                                            Reject
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </strong></h6>
                                                    </div>
                                                </div>
                                                <h3 class="modal-title-details"><i class="fa-solid fa-user-large"></i>
                                                    Personal Details</h3>
                                                <div class="border p-3">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>1. Parivar Pehchan Patra ID</label>
                                                            <h6>{{ $application->family_id ?? 'N/A' }}</h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>2. Name</label>
                                                            <h6>{{ $application->name ?? 'N/A' }}</h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>3. Caste Category</label>
                                                            <h6>{{ $application->caste_category ?? 'N/A' }} </h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>4. Date of Birth</label>
                                                            <h6>{{ $application->date_of_birth ?? 'N/A' }} <a
                                                                    href="{{ url('storage/certificates/' . $application->dob_doc) }}"
                                                                    target="_blank"><i
                                                                        class="fa-solid fa-file-lines"></i></a></h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>5. Age</label>
                                                            <h6>{{ $application->age ?? 'N/A' }}</h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>6. Aadhaar No.</label>
                                                            <h6>{{ $application->aadhaar ?? 'N/A' }}</h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>7. Mailing Address</label>
                                                            <h6>{{ $application->email ?? 'N/A' }}</h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>8. Mobile No.</label>
                                                            <h6>{{ $application->mobile ?? 'N/A' }}</h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>9. Haryana Resident/Domicile</label>
                                                            <h6>
                                                                @if (isset($application->domicile))
                                                                    {{ $application->domicile == 1 ? 'Yes' : 'No' }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                                <a href="{{ url('storage/certificates/' . $application->domicile_doc) }}"
                                                                    target="_blank"><i
                                                                        class="fa-solid fa-file-lines"></i></a>
                                                            </h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>10. Played at Natioanl Level for Haryana</label>
                                                            <h6>
                                                                @if (isset($application->played_national_level))
                                                                    {{ $application->played_national_level == 1 ? 'Yes' : 'No' }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                                <a href="{{ url('storage/certificates/' . $application->national_level_doc) }}"
                                                                    target="_blank"><i
                                                                        class="fa-solid fa-file-lines"></i></a>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h3 class="modal-title-details mt-3"><i
                                                        class="fa-solid fa-user-graduate"></i> Educational Details</h3>
                                                <div class="border p-3 education-area">
                                                    <div class="row">

                                                        @php
                                                            $educationHosp = json_decode($application->educationHosp, true);
                                                        @endphp
                                                        @if (!empty($educationHosp) && is_array($educationHosp))
                                                            @php $count = 1; @endphp

                                                            @foreach ($educationHosp as $qualif)
                                                                @if ($qualif['qualification'])
                                                                    <div class="col-sm-12 col-sm-6 col-md-3">
                                                                        <h6>{{ $qualif['qualification'] }} @if (isset($qualif['other_qualification']))
                                                                                {{ $qualif['other_qualification'] ? ': ' . $qualif['other_qualification'] : '' }}
                                                                            @else
                                                                            @endif
                                                                            <a href="{{ url('storage/education-certificates/' . $qualif['certificate_path']) }}"
                                                                                target="_blank">
                                                                                <i class="fa-solid fa-file-lines"></i><br />
                                                                                <span>Click to View Certificate</span>
                                                                            </a>
                                                                        </h6>
                                                                    </div>
                                                                    @php $count++; @endphp
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <div class="col-sm-12">
                                                                <h6>No qualification records found</h6>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                                <h3 class="modal-title-details mt-3"><i class="fa-solid fa-trophy"></i>
                                                    Achievement Details</h3>
                                                <div class="border p-3">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>1. Physical Disablity</label>
                                                            <h6>
                                                                @if (isset($application->sportsDisciplineHosp->physical_disability))
                                                                    {{ $application->sportsDisciplineHosp->physical_disability == 1 ? 'Yes' : 'No' }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>2. Name of Sports Discipline</label>
                                                            <h6>
                                                                @if ($application->sportsDisciplineHosp && $application->sportsDisciplineHosp->game)
                                                                    {{ $application->sportsDisciplineHosp->game->name }}
                                                                @else
                                                                    <p>No game found.</p>
                                                                @endif
                                                            </h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>3. Name of Tournament</label>
                                                            <h6>{{ $application->sportsDisciplineHosp->tournament->tournament ?? 'N/A' }}
                                                            </h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>4. Organizing Authority</label>
                                                            <h6>{{ $application->sportsDisciplineHosp->organizing_committee ?? 'N/A' }}
                                                            </h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>5. Level of Tournament</label>
                                                            <h6>
                                                                @if (isset($application->sportsDisciplineHosp->tournament_level))
                                                                    {{ $application->sportsDisciplineHosp->tournament_level == '1' ? 'National' : 'International' }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>6 Represented India in any Sports Tournament</label>
                                                            <h6>
                                                                @if (isset($application->sportsDisciplineHosp->represented_india))
                                                                    {{ $application->sportsDisciplineHosp->represented_india == '1' ? 'Yes' : 'No' }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>7. Achievement (Month & Year)</label>
                                                            <h6>{{ $application->sportsDisciplineHosp->achievement_date ?? 'N/A' }}
                                                            </h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>8.Tournament Venu</label>
                                                            <h6>{{ $application->sportsDisciplineHosp->tournament_venue ?? 'N/A' }}
                                                            </h6>
                                                        </div>
                                                        <div class="col-sm-12 col-sm-6 col-md-3 mb-3">
                                                            <label>9. Medal Won</label>
                                                            <h6>{{ $application->sportsDisciplineHosp->medal_won ?? 'N/A' }}
                                                            </h6>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </td>
                            <td>{{ $application->full_name_en ?? 'N/A' }}</td>
                            <td>{{ $application->district ?? 'N/A' }}</td>
                            <!--td>{{ $application->block_town ?? 'N/A' }}</td>
         					<td>{{ $application->ward_village ?? 'N/A' }}</td-->
                            <td>
                                @php
                                    $eventType = $application->sportsDisciplineHosp->event_type ?? null;
                                    echo $eventType == '1' ? 'Individual' : ($eventType == '2' ? 'Team' : 'N/A');
                                @endphp
                            </td>
                            <td>4-years World Cup/Championship</td>
                            <td>{{ $application->sportsDisciplineHosp->organizing_committee ?? 'N/A' }}</td>
                            <!--td>
					@php
						$pd = $application->sportsDisciplineHosp->physical_disability ?? null;
						echo $pd == 1 ? 'Yes' : ($pd == 2 ? 'No' : 'N/A');
					@endphp
					</td>
					<td>
					@php
						$level = $application->sportsDisciplineHosp->tournament_level ?? null;
						echo match ($level) {
							'1' => 'National',
							'2' => 'International',
							default => 'N/A',
						};
					@endphp
					</td>
					<td>Basketball</td>
					{{-- <td>{{ $application->sportsDisciplineHosp->game_id ?? 'N/A' }}</td> --}}
					<td>{{ $application->sportsDisciplineHosp->tournament_venue ?? 'N/A' }}</td>
					<td>
				{{ $application->sportsDisciplineHosp && $application->sportsDisciplineHosp->medal_won
					? ucfirst($application->sportsDisciplineHosp->medal_won)
					: 'N/A' }}
				</td>
					<td>
					{{ $application->sportsDisciplineHosp && $application->sportsDisciplineHosp->achievement_date
						? \Carbon\Carbon::parse($application->sportsDisciplineHosp->achievement_date)->format('d-m-Y')
						: 'N/A' }}
				</td>
				<td>
				{{ $application->sportsDisciplineHosp->represented_india == 1 ? 'International' : 'National' }}
				</td-->
                            <td>
                                @php
                                    $status = $application->status;
                                    $statusText = 'In-Progress';
                                    $badgeClass = 'bg-warning';
                                    $icon = '<i class="fa-solid fa-hourglass-half"></i>';

                                    if ($status == '1') {
                                        $statusText = 'Approved';
                                        $badgeClass = 'bg-success';
                                        $icon = '<i class="fa-solid fa-thumbs-up"></i>';
                                    } elseif ($status == '2') {
                                        $statusText = 'Rejected';
                                        $badgeClass = 'bg-danger';
                                        $icon = '<i class="fa-solid fa-ban"></i>';
                                    }
                                @endphp

                                <span class="badge {{ $badgeClass }}">{!! $icon !!}
                                    {{ $statusText }}</span>
                            </td>
                            @if ($application->status == '0')
													<!--td>
							<form action="{{ route('hq.approveReject', $application->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to Approve this?');">
							@csrf
							<input type="text" value="1" name="status" hidden>
							<button type="submit" class="btn btn-success btn-sm mb-1">Approve</button>
							</form>
							<form action="{{ route('hq.approveReject', $application->id) }}" method="POST" style="display:inline;"onsubmit="return confirm('Are you sure you want to Reject this?');">
							@csrf
							<input type="text" value="2" name="status" hidden>
							<button type="submit" class="btn btn-danger btn-sm">Reject</button>
							</form>
							</td-->
                            @endif
                        </tr>
                    @endforeach
                @endforeach


            </tbody>
        </table>

    </div>

    </div>




    </div>
@endsection
<script src="{{ url('assets/js/jquery.min.js') }}"></script>
<script src="{{ url('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#img-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function() {
        readURL(this);
    });

    $(document).ready(function() {
        $('.navbar-toggler').click(function() {
            $('aside').toggleClass('main');
        });

    });
</script>
<script>
    function showRemarks(id) {
        document.getElementById('remarksBox-' + id).style.display = 'block';
        document.getElementById('rejectBtn-' + id).style.display = 'none';
    }
</script>
