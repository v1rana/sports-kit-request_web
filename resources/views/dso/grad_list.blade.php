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
                    <!--th>Gender</th>
                    <th>State</th>
                    <th>Sports Discipline</th-->
                    <th>Tournament Name</th>
                    <th>Month/Year</th>
                    <!--th>Venue</th-->
                    <th>Organising Authority</th>
                    <th>Tournament Type</th>
                    <th>Medal Won</th>
                    <!--th>Participation Level</th>
                    <th>Application Date</th>
                    <th>Letter Of Enquiry</th>
                    <th>Reply Of Letter</th-->
                    <th>Application Status</th>
                    <th>Download PDF</th>
                    <!--th>Upload Certificate</th-->
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sportsCertificates as $index => $certificate)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $certificate->certificate_no }}</td>
                    <td>{{ $certificate->sports_person_name }}</td>
                    <!--td>{{ $certificate->gender }}</td>
                    <td>{{ $certificate->domicile_state }}</td>
                    <td>{{ $certificate->name_sports_discipline }}</td-->
                    <td>{{ $certificate->tournament }}</td>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $certificate->month_year)->format('F Y') }}</td>
                    <!--td>{{ $certificate->venue_of_tournament }}</td-->
                    <td>{{ $certificate->authority }}</td>
                    <td>{{ $certificate->tournament_type }}</td>
                    <td>{{ $certificate->medal_won }}</td>
                    <!--td>{{ $certificate->participation_level }}</td>
                    <td>{{ $certificate->created_at }}</td>
					<td>
    @if($certificate->enquiry_pdf)
        <div class="card p-3 mb-3">
    <a href="{{ asset('storage/' . $certificate->enquiry_pdf) }}" 
       target="_blank" 
       class="btn btn-primary w-100 mb-2">
        📄 View Letter
    </a>
    <p class="mb-0 text-muted text-end">
        <small>
            📅 Datetime: {{ \Carbon\Carbon::parse($certificate->enquiry_pdf_datetime)->format('d M Y, h:i A') }}
        </small>
    </p>
</div>

    @else
        <form action="{{ route('dso.enquiry.UploadLetter') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
            @csrf
            <input type="file" name="enquiry_pdf" accept="application/pdf" required class="form-control mb-2">
            <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
            <button type="submit" class="btn btn-danger w-100">
                Upload Letter
            </button>
        </form>
    @endif
</td-->
<!--td>
    @if($certificate->replied_pdf)
       <div class="card p-3 mb-3">
    <a href="{{ asset('storage/' . $certificate->replied_pdf) }}" 
       target="_blank" 
       class="btn btn-primary w-100 mb-2">
        📄 View Reply Letter
    </a>
    <p class="mb-0 text-muted text-end">
        <small>
            📅 Datetime: {{ \Carbon\Carbon::parse($certificate->replied_pdf_datetime)->format('d M Y, h:i A') }}
        </small>
    </p>
</div>

    @else
        <form action="{{ route('dso.enquiry.ReplyLetter') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
            @csrf
            <input type="file" name="replied_pdf" accept="application/pdf" required class="form-control mb-2">
            <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
            <button type="submit" class="btn btn-danger w-100">
                Upload Reply of Letter
            </button>
        </form>
    @endif
</td-->
                    <td>
                        <strong>
                            @if($certificate->status == 'Approved')
                                <span class="badge rounded-pill bg-success"><i class="fa-solid fa-thumbs-up"></i> Approved</span>
                            @elseif($certificate->status == 'Rejected')
                                <span class="badge rounded-pill bg-danger"><i class="fa-solid fa-ban"></i>  Rejected</span>
                            @else
                                <form action="{{ route('dso.approve', $certificate->id) }}" method="POST" style="display:inline;">
									@csrf
									<button type="submit"  class="btn btn-success w-100 mb-2">
								 Approved
							</button>
								</form>

								<form action="#" method="POST" style="display:inline;">
									@csrf
									<!--button type="button" id="" class="btn btn-danger w-100">
										Rejected
									</button-->
									<!-- Button trigger modal -->
<button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectRemarkModal">
  Rejected
</button>

<!-- Modal -->
<div class="modal fade" id="rejectRemarkModal" tabindex="-1" aria-labelledby="rejectRemarkModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-body">
		<h5>Rejection Remarks</h5>
        <textarea class="form-control"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
								</form>
                            @endif
                        </strong>
                <!--@if($certificate->status == 'Pending')
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
                @endif-->
            </td>
			<td>
				<form action="{{ route('dso.certificates.downloadPDF') }}" method="POST" style="display:inline;" target="_blank">
					@csrf
					<button  type="submit" class="btn btn-danger w-100">
						PDF
					</button>
                </form>
			</td>
			<!--td>
			@if(!empty($certificate->enquiry_pdf) && !empty($certificate->replied_pdf))
    @if($certificate->certificate_pdf)
        <a href="{{ asset('storage/' . $certificate->certificate_pdf) }}" target="_blank" class="btn btn-primary w-100 mb-2">
            View Certificate
        </a>
    @else
        <form action="{{ route('dso.certificates.uploadPDF') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
            @csrf
            <input type="file" name="certificate_pdf" accept="application/pdf" required class="form-control mb-2">
            <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
            <button type="submit" class="btn btn-danger w-100">
                Upload Certificate
            </button>
        </form>
    @endif
    @endif
</td-->
<td>
<button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modal{{ $certificate->id }}">
  View
</button>

<!-- Modal -->
<div class="modal fade" id="modal{{ $certificate->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal{{ $certificate->id }}">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>Application Submitted Date</label>
				<h6>2 Apr 2025, 04:35 PM</h6>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>Application Status</label>
				<h6> <strong>
                            @if($certificate->status == 'Approved')
                                <span class="badge rounded-pill bg-success"><i class="fa-solid fa-thumbs-up"></i> Approved</span>
                            @elseif($certificate->status == 'Rejected')
                                <span class="badge rounded-pill bg-danger"><i class="fa-solid fa-ban"></i>  Rejected</span>
                            @else
                                <form action="{{ route('dso.approve', $certificate->id) }}" method="POST" style="display:inline;">
									@csrf
									<button type="submit"  class="btn btn-success">
								 Approved
							</button>
								</form>

								<form action="#" method="POST" style="display:inline;">
									@csrf
									<a href="javascript:void(0);" id="reject-application" class="btn btn-danger">
										Rejected
									</a>
									
								</form>
                            @endif
                        </strong></h6>
			</div>
			
			<div class="col-xs-12 col-sm-6 col-md-3">
			<div class="rejection-remarks"><label>Remarks</label><div class="d-flex"><input type="text" class="form-control" /><button class="btn btn-primary " type="submit">Submit</button></div></div>
			</div>
			
			<div class="col-xs-12 col-sm-12 col-md-12">
				<label>Tournament Name</label>
				<h6>{{ $certificate->tournament }}</h6>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>Certificate Number</label>
				<h6>25PAGO4D258</h6>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>Sports Person Name</label>
				<h6>{{ $certificate->sports_person_name }}</h6>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>Gender</label>
				<h6>{{ $certificate->gender }}</h6>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>State</label>
				<h6>{{ $certificate->domicile_state }}</h6>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>Sports Discipline</label>
				<h6>{{ $certificate->name_sports_discipline }}</h6>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>Month/Year</label>
				<h6>{{ \Carbon\Carbon::createFromFormat('Y-m', $certificate->month_year)->format('F Y') }}</h6>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>Venue</label>
				<h6>{{ $certificate->venue_of_tournament }}</h6>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6">
				<label>Organising Authority</label>
				<h6>{{ $certificate->authority }}</h6>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>Tournament Type</label>
				<h6>{{ $certificate->tournament_type }}</h6>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>Medal Won</label>
				<h6>{{ $certificate->medal_won }}</h6>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>Participation Level</label>
				<h6>{{ $certificate->participation_level }}</h6>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>Letter of Enquiry</label>
				@if($certificate->enquiry_pdf)
				<div>
					<a href="{{ asset('storage/' . $certificate->enquiry_pdf) }}" target="_blank" class="btn btn-primary mb-2">
						<i class="fa-solid fa-file-lines"></i> View Letter
					</a>
					<p>
						<small class="mb-0 text-muted text-end">
							📅 Datetime: {{ \Carbon\Carbon::parse($certificate->enquiry_pdf_datetime)->format('d M Y, h:i A') }}
						</small>
					</p>
				</div>

				@else
					<form action="{{ route('dso.enquiry.UploadLetter') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
						@csrf
						<input type="file" name="enquiry_pdf" accept="application/pdf" required class="form-control mb-2">
						<input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
						<button type="submit" class="btn btn-success">
							Upload Letter
						</button>
					</form>
				@endif

			</div>
		
			
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>Reply of Letter</label>
				@if($certificate->replied_pdf)
       <div>
    <a href="{{ asset('storage/' . $certificate->replied_pdf) }}" target="_blank" class="btn btn-primary mb-2">
        <i class="fa-solid fa-file-lines"></i> View Reply Letter
    </a>
    
        <p><small  class="mb-0 text-muted text-end">
            📅 Datetime: {{ \Carbon\Carbon::parse($certificate->replied_pdf_datetime)->format('d M Y, h:i A') }}
        </small></p>
    
</div>

    @else
        <form action="{{ route('dso.enquiry.ReplyLetter') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
            @csrf
            <input type="file" name="replied_pdf" accept="application/pdf" required class="form-control mb-2">
            <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
            <button type="submit" class="btn btn-success">
                Upload Reply of Letter
            </button>
        </form>
    @endif
			</div>
			
			<div class="col-xs-12 col-sm-6 col-md-3">
				<label>Download Certificate</label>
				<h6><form action="{{ route('dso.certificates.downloadPDF') }}" method="POST" style="display:inline;" target="_blank">
					@csrf
					<button  type="submit" class="btn btn-success">
						<i class="fa-solid fa-file-arrow-down"></i> PDF
					</button>
                </form></h6>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				
				@if(!empty($certificate->enquiry_pdf) && !empty($certificate->replied_pdf))
    @if($certificate->certificate_pdf)
				<h6>
        <a href="{{ asset('storage/' . $certificate->certificate_pdf) }}" target="_blank" class="btn btn-primary mb-2">
            View Certificate
        </a>
    @else
				<label>Upload Signed Certificate</label>
        <form action="{{ route('dso.certificates.uploadPDF') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
            @csrf
            <input type="file" name="certificate_pdf" accept="application/pdf" required class="form-control mb-2">
            <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
            <button type="submit" class="btn btn-success">
                Upload Certificate
            </button>
        </form>
    @endif
    @endif</h6>
			</div>
			
			
		</div>
      </div>
      <div class="modal-footer justify-content-between">
		<h5 class="text-danger" >
			Application Timeline - 20Days
		</h5>
        <div>
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary">Save changes</button>
      </div>
	  </div>
    </div>
  </div>
</div>

</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
	$(document).ready(function(){
		$('#reject-application').click(function(){	
			$('.rejection-remarks').show();
		});		
	});
</script>

@endsection
