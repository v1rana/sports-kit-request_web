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
    label.info-label,small.info-label {font-weight: 500;font-size:14px;display: block; margin-bottom:4px; line-height:normalg}
</style>
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
                    <th>Application Id</th>
                    <th>Certificate No</th>
                    <th>Sports Person Name</th>
                    <th>Tournament Name</th>
                    <th>Month/Year</th>
                    <th>Organising Authority</th>
                    <th>Tournament Type</th>
                    <th>Medal Won</th>
                    <th>Action</th>
                    <th>Download Certificate</th>
                    <th>Application Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sportsCertificates as $index => $certificate)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $certificate->appl_id }}</td>
                    <td>{{ $certificate->certificate_no }}</td>
                    <td>{{ $certificate->sports_person_name }}</td>
                    <td>{{ $certificate->tournament }}</td>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $certificate->month_year)->format('F Y') }}</td>
                    <td>{{ $certificate->authority }}</td>
                    <td>{{ $certificate->tournament_type }}</td>
                    <td>{{ $certificate->medal_won }}</td>
                    <td>
<button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modal{{ $certificate->id }}">
  View
</button>

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
                <h5> {{ \Carbon\Carbon::parse($certificate->created_at)->format('d M Y, h:i A') }}</h5>
            </div>
                <div class="co">
                    <small class="info-label text-muted ">Certificate Number</small>
                    <h5>{{ $certificate->certificate_no }}</h5>
                </div>
            <div class="">
                <label class="info-label text-muted ">Application Status</label>
                <h6>
                    <strong> 
                        @if($certificate->status == 'Approved')
                            <span class="badge rounded-pill bg-success"><i class="fa-solid fa-thumbs-up"></i> Approved</span>
                        @elseif($certificate->status == 'Rejected')
                            <span class="badge rounded-pill bg-danger"><i class="fa-solid fa-ban"></i>  Rejected</span>
						 @elseif(!empty($certificate->enquiry_pdf) && !empty($certificate->replied_pdf))	
						 <form action="{{ route('hq.approve', $certificate->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to Approve this?');">
                                @csrf
                                <button type="submit"  class="btn btn-success">  Approve </button>
                            </form>
                            <a href="javascript:void(0);" id="reject-application" class="btn btn-danger">Reject</a>
                        @else
                            
                        @endif
                    </strong>
                </h6>
            </div>
                
            <div class="">
                <div class="rejection-remarks">
                    <label class="info-label text-muted ">Rejection Remarks</label>
                    <div class="d-flex">
                        <form action="{{ route('hq.reject', $certificate->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to Reject this?');">
                            @csrf<textarea class="form-control" name="rejection_remark" required></textarea>
                            <button class="btn btn-primary " type="submit">Submit</button>
                        </form>
                    </div>
                </div>
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
                    <img src="http://164.100.137.70/storage/uploads/xO18eDzRgOertwfn2x9YE3IOlSUkpzR0dVH33DF7.jpg" width="150px" height="150px" style="border:5px solid #eee">
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

        <h3 class="modal-title-details"><i class="fa-solid fa-folder-open"></i> Uploaded Documents</h3>
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
    							{{ \Carbon\Carbon::parse($certificate->enquiry_pdf_datetime)->format('d M Y, h:i A') }}
    						</small>
    					</p>
    				</div>

    				@else
    					<form action="{{ route('hq.enquiry.UploadLetter') }}" method="POST" enctype="multipart/form-data" style="display:flex;">
    						@csrf
    						<input type="file" name="enquiry_pdf" accept="application/pdf" required class="form-control">
    						<input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
    						<button type="submit" class="btn btn-warning" style=" margin-left: 3px;">
    							Upload
    						</button>
    					</form>
    				@endif

    			</div>
    		
			
    			<div class="col-xs-12 col-sm-6 col-md-4">
    				<label class="info-label text-muted ">Reply of Letter</label>
    				@if($certificate->replied_pdf)
                    <div>
                        <a href="{{ asset('storage/' . $certificate->replied_pdf) }}" target="_blank" class="btn btn-primary mb-2"> <i class="fa-solid fa-file-lines"></i> View Reply Letter  </a>
        
                        <p><small  class="mb-0 text-muted text-end">
                             {{ \Carbon\Carbon::parse($certificate->replied_pdf_datetime)->format('d M Y, h:i A') }}
                        </small></p>
                    </div>

                    @else
                    <form action="{{ route('hq.enquiry.ReplyLetter') }}" method="POST" enctype="multipart/form-data" style="display:flex;">
                        @csrf
                        <input type="file" name="replied_pdf" accept="application/pdf" required class="form-control">
                        <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
                        <button type="submit" class="btn btn-warning" style="margin-left:5px">
                            Upload
                        </button>
                    </form>
                    @endif
    			</div>
    			
    			<div class="col-xs-12 col-sm-6 col-md-3">
    				<label class="info-label text-muted ">Download Certificate</label>
					
    				<h6><a href="{{ url('storage/'.($certificate->verification_by_sportsperson)) }}" target="_blank" >
    					<button  type="submit" class="btn btn-success">
    						<i class="fa-solid fa-file-arrow-down"></i> PDF
    					</button></a>
                    </h6>
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
    $createdAt = \Illuminate\Support\Carbon::parse($certificate->created_at);

    // Use certificate_upload_datetime if available, otherwise use now
    $endDate = $certificate->certificate_upload_datetime
        ? \Illuminate\Support\Carbon::parse($certificate->certificate_upload_datetime)
        : now();

    // Calculate only full days (ignore fractions)
    $totalHours = $createdAt->diffInHours($endDate);
    $totalDays = floor($totalHours / 24);
@endphp

<h5 class="text-danger">
    Application Timeline - {{ $totalDays }} Days
</h5>




        <div>
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			<!--<button type="button" class="btn btn-primary">Save changes</button>-->
      </div>
	  </div>
    </div>
  </div>
</div>

</td>
                   
			<td>
						@if($certificate->verification_by_sportsperson)
							<a href="{{ url('storage/'.($certificate->verification_by_sportsperson)) }}" target="_blank" class="btn btn-danger w-100">
    					View PDF</a>
						@else
							<button class="btn btn-secondary w-100" disabled>
								No Document Available
							</button>
						@endif
					</td>
					
					 <td>
                        <strong>
                            @if($certificate->status == 'Approved')
                                <span class="badge rounded-pill bg-success"><i class="fa-solid fa-thumbs-up"></i> Approved</span>
                            @elseif($certificate->status == 'Rejected')
                                <span class="badge rounded-pill bg-danger"><i class="fa-solid fa-ban"></i>  Rejected</span>
                            @else
								<span class="badge rounded-pill bg-info w-100"><i class="fa-solid fa-check"></i> In-Progress</span>


                            @endif
                        </strong>
               
            </td>
			

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
 <script src="{{ url('assets/js/jquery.min.js') }}"></script>
		<script src="{{ url('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
	$(document).ready(function(){
		$('#reject-application').click(function(){	
			$('.rejection-remarks').show();
		});		
	});
</script>

@endsection
