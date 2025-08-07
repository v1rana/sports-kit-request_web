@extends('hq_main')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<style>
    .modal-title-details{background: rgba(0, 0, 0, 0.04);
    padding: 10px;margin:0;
    color: #36454F;
    font-size: 20px;
    text-transform: uppercase;}
    label.info-label,small.info-label {font-weight: 500;font-size:14px;display: block; margin-bottom:4px; line-height:normal}
	
.table td{vertical-align:top}
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
  .table .btn{font-size: 15px;padding:1px 0 0 0;    margin-top: 2px;}
  ul.list-unstyled li:before {
    position: absolute;
    content: "";
    background: rgba(0, 0, 0, 0.7);
	border-radius: 100%;
    width: 4px;
    height: 4px;
    top: 8px;
    left: -6px;
}
ul.list-unstyled li {
    position: relative;
}
</style>
<h4 class="mb-4">Sports Gradation Certificates List 
    <a href="{{ url()->previous() }}" class="btn btn-secondary float-end">
        <i class="fa-solid fa-arrow-left-long"></i> Back
    </a>
</h4>

<div class=" bg-white shadow mb-5 p-2 table-responsive table-container w-100">
	<table class="table table-bordered bg-white table-hover">
            <thead>
                <tr class="bg-primary text-white">
                    <th>Sr. No.</th>
                    <th>Application Id</th>
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
                    <th>Reply Of Letter</th>
                    <th>Action</th>
                    <th>Download Certificate</th-->
                    <!--th>Upload Certificate</th-->
                    <th>Application Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sportsCertificates as $index => $certificate)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
						<div class="d-flex">
							<button type="button" class="bg-transparent text-primary app-id-view-btn" data-bs-toggle="modal" data-bs-target="#modal{{ $certificate->id }}">{{ $certificate->appl_id }}</button>
							@if($certificate->verification_by_sportsperson)
							<a href="{{ url('storage/'.($certificate->verification_by_sportsperson)) }}" target="_blank" class="btn btn-primary"><i class="fa-solid fa-file-arrow-down"></i></a>
						@else
							<button class="btn btn-secondary w-100" disabled>
								No Document Available
							</button>
						@endif
							
						</div>
						
						<!-- Modal -->
						
						<div class="modal fade" id="modal{{ $certificate->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
						  <div class="modal-dialog modal-dialog-centered modal-xl">
							<div class="modal-content">
							  <div class="modal-header">
								<h5 class="modal-title float-start" id="modal{{ $certificate->id }}">Gradation Application ID  - <span>{{ $certificate->appl_id }}</span></h5>
								<h5 class="modal-title float-end" id="modal{{ $certificate->id }}">Certificate Grade - <span>{{ $certificate->gradation }}</span> </h5>
								
							  </div>
							  <div class="modal-body">
								<div class="d-flex justify-content-between mb-3">
									<div class="">
										<label class="info-label text-muted ">Application Submitted Date</label>
										<h5> {{ \Carbon\Carbon::parse($certificate->date)->format('d M Y') }}</h5>
									</div>
										<div class="co">
											@if($certificate->status == 'Approved')<small class="info-label text-muted ">Certificate Number</small>
											<h5>{{ $certificate->certificate_no }}</h5>@endif
										</div>
									<div class="">
										<label class="info-label text-muted ">Application Status</label>
										<h6>
											<strong> 
											<div id="approval-section-{{ $certificate->id }}" style="{{ (!empty($certificate->enquiry_pdf) && !empty($certificate->replied_pdf) && $certificate->status != 'Approved' && $certificate->status != 'Rejected') ? '' : 'display:none;' }}">
    <form action="{{ route('hq.approve', $certificate->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to Approve this?');">
        @csrf
        <button type="submit" class="btn btn-success">Approve</button>
    </form>
    <a href="javascript:void(0);" id="reject-application-{{ $certificate->id }}" class="btn btn-danger">Reject</a>	
</div>
										@if($certificate->status == 'Approved' && empty($certificate->certificate_pdf))
											<span class="badge rounded-pill bg-success"><i class="fa-solid fa-thumbs-up"></i> Approved</span>
										@elseif($certificate->status == 'Approved' && !empty($certificate->certificate_pdf))
											<span class="badge rounded-pill bg-success"><i class="fa-solid fa-thumbs-up"></i> Certificate Issued</span>
										@elseif($certificate->status == 'Rejected')
											<span class="badge rounded-pill bg-danger"><i class="fa-solid fa-ban"></i>  Rejected</span>
										
										@else
											
										@endif
									</strong>
								</h6>
							</div>
								
							
								</div>
								<h3 class="modal-title-details"><i class="fa-solid fa-user-large"></i> Personal Details</h3>
								<div class="border p-3">
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-10">
											<div class="row">
												<div class="col-md-3 mb-3">
													<small class="info-label text-muted ">1. Sports person's Name</small>
													<h5>{{ $certificate->sports_person_name }}</h5>
												</div>
										<div class="col-md-3 mb-3">
											<small class="info-label text-muted ">2. Aadhaar No.</small>
											<h5>{{ $certificate->aadhaar_no }}</h5>
										</div>
										<div class="col-md-3 mb-3">
											<small class="info-label text-muted ">3. Mobile No.</small>
											<h5>{{ $certificate->mobile_no }}</h5>
										</div>
										<div class="col-md-3 mb-3">
											<small class="info-label text-muted ">4. Name of District sportsperson belongs to</small>
											<h5>{{ $certificate->district_sportsperson_belongs }}</h5>
										</div>
										<div class="col-md-3 mb-3">
											<small class="info-label text-muted ">5. Domicile State</small>
											<h5>{{ $certificate->domicile_state }}</h5>
										</div>
										  <div class="col-md-3 mb-3">
											<small class="info-label text-muted ">6.  Plays for (Name of State/ Organization)</small>
											<h5>{{ $certificate->plays_for_statte_org }}</h5>
										</div>
										<div class="col-md-3 mb-3">
											<small class="info-label text-muted ">7. Name of Sports Discipline</small>
											<h5>{{ $certificate->name_sports_discipline }}</h5>
										</div>
										<div class="col-md-3 mb-3">
											<small class="info-label text-muted ">8. Type of Event</small>
											<h5>{{ $certificate->tournament_type }}</h5>
										</div>
									</div>
										</div>
						   
										<div class="col-xs-12 col-sm-12 col-md-2">
											<img src="{{ asset('storage/' . $certificate->profile_picture) }}" width="150px" height="150px" style="border:5px solid #eee">

										</div>
									</div> 
								</div>

								<h3 class="modal-title-details mt-3"><i class="fa-solid fa-trophy"></i> Achievement Details</h3>
								<div class="border p-3">
									<div class="row">
										<div class="col-xs-12 col-sm-6 col-md-8 mb-3">
											<small class="info-label text-muted ">1. Name of Tournament</small>
											<h5>{{ $certificate->tournament }}</h5>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-2 mb-3">
											 <small class="info-label text-muted ">2. Month/Year</small>
											<h5>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $certificate->month_year)->format('F Y') }}</h5>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-2 mb-3">
											 <small class="info-label text-muted ">3. Venue of Tournament</small>
											<h5>{{ $certificate->venue_of_tournament }}</h5>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-8 mb-3">
											<small class="info-label text-muted ">4. Organising Authority</small>
											<h5>{{ $certificate->authority }}</h5>
										</div>
										<!--div class="col-xs-12 col-sm-6 col-md-3">
											<label>Gender</label>
											<h6>{{ $certificate->gender }}</h6>
										</div-->
										
										<div class="col-xs-12 col-sm-6 col-md-2 mb-3">
											<small class="info-label text-muted ">5. Medal Won</small>
											<h5>{{ $certificate->medal_won }}</h5>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-2 mb-3">
											<small class="info-label text-muted ">6. Participation Level</small>
											<h5>{{ $certificate->participation_level }}</h5>
										</div>
									</div>
								</div>

								<h3 class="modal-title-details"><i class="fa-solid fa-folder-open"></i> Application Enquiry</h3>
								<div class="border p-3">       
									<div class="row">
										<div class="col-xs-12 col-sm-6 col-md-4">
											<label class="info-label text-muted ">Letter of Enquiry</label>
											@if($certificate->enquiry_pdf)
											<div>
												<a href="{{ asset('storage/' . $certificate->enquiry_pdf) }}" target="_blank" class="btn btn-primary mb-2">
													<i class="fa-solid fa-file-lines"></i> View Letter
												</a>
												<p>
													<small class="mb-0 text-muted text-end">
														Date: {{ \Carbon\Carbon::parse($certificate->enquiry_pdf_datetime)->format('d M Y') }}
													</small>
												</p>
											</div>

											@else
												<form id="enquiry-upload-form-{{ $certificate->id }}" action="{{ route('hq.enquiry.UploadLetter') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="enquiry_pdf" accept="application/pdf" required class="form-control">
    <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
    <button type="submit" class="btn btn-warning mt-2">Upload</button>
</form>
<div id="upload-status-{{ $certificate->id }}"></div>

											@endif

										</div>
									
									
										<div class="col-xs-12 col-sm-6 col-md-4">
											<label class="info-label text-muted ">Reply of Letter</label>
											@if($certificate->replied_pdf)
											<div>
												<a href="{{ asset('storage/' . $certificate->replied_pdf) }}" target="_blank" class="btn btn-primary mb-2"> <i class="fa-solid fa-file-lines"></i> View Reply Letter  </a>
								
												<p><small  class="mb-0 text-muted text-end">
													Date: {{ \Carbon\Carbon::parse($certificate->replied_pdf_datetime)->format('d M Y') }}
												</small></p>
											</div>

											@else
											<form id="reply-upload-form-{{ $certificate->id }}" action="{{ route('hq.enquiry.ReplyLetter') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="replied_pdf" accept="application/pdf" required class="form-control">
            <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
            <button type="submit" class="btn btn-warning mt-2" style="margin-left:5px">Upload</button>
        </form>
        <div id="reply-upload-status-{{ $certificate->id }}" class="mt-2"></div>
											@endif
										</div>
										
										<div class="col-xs-12 col-sm-6 col-md-3">
										@if(!empty($certificate->enquiry_pdf) && !empty($certificate->replied_pdf) && ($certificate->status == 'Approved'))
											<label class="info-label text-muted">Download Certificate</label>
											<form id="pdfForm{{ $certificate->id }}" method="POST" action="{{ route('hq.certificates.downloadPDF') }}" target="_blank">
												@csrf
												<input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
												<button type="submit" class="btn btn-success">
													<i class="fa-solid fa-file-arrow-down"></i> PDF
												</button>
											</form>
											@endif
										</div>

										<div class="col-xs-12 col-sm-6 col-md-3">
											
											@if(!empty($certificate->enquiry_pdf) && !empty($certificate->replied_pdf) && ($certificate->status == 'Approved'))
								@if($certificate->certificate_pdf)
											<h6>
									<a href="{{ asset('storage/' . $certificate->certificate_pdf) }}" target="_blank" class="btn btn-primary mb-2">
										View Certificate
									</a>
								@else
											<label>Upload Signed Certificate</label>
									<form action="{{ route('hq.certificates.uploadPDF') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
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
								@php
							$createdAt = \Illuminate\Support\Carbon::parse($certificate->date);

							// Use certificate_upload_datetime if available, otherwise use now
							$endDate = $certificate->certificate_upload_datetime
								? \Illuminate\Support\Carbon::parse($certificate->certificate_upload_datetime)
								: now();

							// Calculate only full days (ignore fractions)
							$totalHours = $createdAt->diffInHours($endDate);
							$totalDays = floor($totalHours / 24);
						@endphp

						<h5 class="text-danger">
							Application Enquiry Timeline - {{ $totalDays }} Days
						</h5>




								<div>
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
									<!--<button type="button" class="btn btn-primary">Save changes</button>-->
							  </div>
							  </div>
							</div>
						</div>
					</div>
					</div>
					
					</td>
                    <td>{{ $certificate->certificate_no }}</td>
                    <td>{{ $certificate->sports_person_name }}</td>
                    <!--td>{{ $certificate->gender }}</td>
                    <td>{{ $certificate->domicile_state }}</td>
                    <td>{{ $certificate->name_sports_discipline }}</td-->
                    <td>{{ $certificate->tournament }}</td>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $certificate->month_year)->format('F Y') }}</td>
                    <!--td>{{ $certificate->venue_of_tournament }}</td-->
                    <td>{{ $certificate->authority }}</td>
                    <td>{{ $certificate->tournament_type }}</td>
                    <td>{{ $certificate->medal_won }}</td>
                   
                   
					
					 <td>
                        <strong> 
							@if($certificate->status == 'Approved' && empty($certificate->certificate_pdf))
								<span class="badge rounded-pill bg-success"><i class="fa-solid fa-thumbs-up"></i> Approved</span>
							@elseif($certificate->status == 'Approved' && !empty($certificate->certificate_pdf))
								<span class="badge rounded-pill bg-success"><i class="fa-solid fa-thumbs-up"></i> Certificate Issued</span>
								<br>
								<a href="{{ asset('storage/' . $certificate->certificate_pdf) }}" 
								   target="_blank" 
								   style="text-decoration: underline; color: #0d6efd;font-size: 9px;" class="mt-2 d-inline-block">
									View Issued Certificate
								</a>
                            @elseif($certificate->status == 'Rejected')
                                <span class="badge rounded-pill bg-danger"><i class="fa-solid fa-ban"></i>  Rejected</span>
                            @else
								<span class="badge rounded-pill bg-primary w-100"><i class="fa-solid fa-check"></i> In-Progress</span>
                               <!-- <form action="{{ route('hq.approve', $certificate->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to Approve this?');">
									@csrf
									<button type="submit"  class="btn btn-success w-100 mb-2">
								 Approve
							</button>
								</form>

								
<button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectRemarkModal{{ $certificate->id }}">
  Reject
</button>-->

<!-- Modal -->
<div class="modal fade" id="rejectRemarkModal{{ $certificate->id }}" tabindex="-1" aria-labelledby="rejectRemarkModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('hq.reject', $certificate->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to Reject this?');">
        @csrf
        <div class="modal-body">
          <h5>Rejection Remarks</h5>
          <textarea class="form-control" name="rejection_remark" required></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

                            @endif
                        </strong>
                <!--@if($certificate->status == 'Pending')
                    <form action="{{ route('hq.approve', $certificate->id) }}" method="POST" style="display:inline;">
                        @csrf
						<button type="submit"  class="btn btn-success w-100 mb-2">
                     Approved
                </button>
                    </form>

                    <form action="{{ route('hq.reject', $certificate->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">
                            Rejected
                        </button>
                    </form>
                @endif-->
            </td>
			<!--td>
			@if(!empty($certificate->enquiry_pdf) && !empty($certificate->replied_pdf))
    @if($certificate->certificate_pdf)
        <a href="{{ asset('storage/' . $certificate->certificate_pdf) }}" target="_blank" class="btn btn-primary w-100 mb-2">
            View Certificate
        </a>
    @else
        <form action="{{ route('hq.certificates.uploadPDF') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('[id^="enquiry-upload-form-"]');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const certId = formData.get('certificate_id');
            const statusDiv = document.getElementById(`upload-status-${certId}`);

            // Disable form while uploading
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Uploading...';

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Show success and update UI
                    statusDiv.innerHTML = `
                        <a href="${data.file_url}" target="_blank" class="btn btn-primary mt-2">
                            <i class="fa-solid fa-file-lines"></i> View Letter
                        </a>
                        <p>
                            <small class="mb-0 text-muted text-end">Date: ${data.upload_date}</small>
                        </p>
                    `;
                    form.remove(); // Remove the form after success
                } else {
                    alert('Upload failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                alert('Upload failed due to error.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Upload';
            });
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const replyForms = document.querySelectorAll('[id^="reply-upload-form-"]');

    replyForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const certId = formData.get('certificate_id');
            const statusDiv = document.getElementById(`reply-upload-status-${certId}`);
            const approvalDiv = document.getElementById(`approval-section-${certId}`); // jo div tumne blade me add kiya, initially hidden
            const submitBtn = form.querySelector('button[type="submit"]');

            submitBtn.disabled = true;
            submitBtn.textContent = 'Uploading...';

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Show the uploaded file link
                    statusDiv.innerHTML = `
                        <a href="${data.file_url}" target="_blank" class="btn btn-primary mt-2">
                            <i class="fa-solid fa-file-lines"></i> View Reply
                        </a>
                        <p>
                            <small class="mb-0 text-muted text-end">Date: ${data.upload_date}</small>
                        </p>
                    `;

                    form.remove(); // remove the upload form

                    // Show the approval buttons div which was hidden initially
                    if (approvalDiv) {
                        approvalDiv.style.display = 'block';
                    }
                } else {
                    statusDiv.innerHTML = `<p class="text-danger">Upload failed: ${data.message}</p>`;
                }
            })
            .catch(error => {
                statusDiv.innerHTML = `<p class="text-danger">Upload error.</p>`;
                console.error('Upload error:', error);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Upload';
            });
        });
    });
});

</script>



@endsection
