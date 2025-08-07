@extends('layouts.dso_main')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<style>
	    label.info-label,small.info-label {font-weight: 500;font-size:17px;display: block; margin-bottom:0; line-height:normal}
	    .modal-body h5{margin:0; margin-left: 16px;}
	    .games-authorised-sec .row > div{font-size: 15px;padding:0}
	    .games-authorised-sec .row > div h6 {
    margin: 0;
    padding: 6px 10px;
    border-left: 1px solid rgba(0, 0, 0, 0.07);
    background: #eee;
    color: #777;
}
.games-authorised-sec .row > div:first-child h6{border:none;}
.games-authorised-sec .row > div:nth-child(-n+4){border-top:0;}
.games-authorised-sec .row > div p{padding: 5px 10px;font-size:14px;}

  .custom-header-row {
	background: linear-gradient(135deg, #b63807, #a78f21, #f78b2d);
    /* background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);  */
    color: #f8f9fa;
    font-weight: 700;
    text-transform: capitalize;
    letter-spacing: 0.8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    border-radius: 8px 8px 0 0;
  }

  .custom-header-row th {
    padding: 8px;
	vertical-align: middle;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  }

  table {
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 10px;
    overflow: hidden;
    background-color: #ffffff;
  }

  tbody tr:nth-child(even) {
    background-color: #f4f6f8;
  }

  tbody tr:nth-child(odd) {
    background-color:rgb(230, 230, 230);
  }

  tbody tr:hover {
    background-color: #d9e4f5;
    transition: all 0.3s ease;
    cursor: pointer;
  }

  td {
    padding: 14px;
    vertical-align: middle;
    font-size: 15px;
  }

  /* Optional: Subtle border and shadow on the table */
  .table-container {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    /* overflow: hidden; */
  }

  .table .btn{font-size: 15px;padding:4px 0 0 0;    margin-top: 2px;}
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

a.badge {border:none;
    height: auto;font-weight:normal;
    font-size: 11px;
    line-height: normal;margin-top:4px; padding: 3px;transition:all linear 0.1s 0s;
    box-shadow:0 4px 0 #193c6b;
}
a.badge:hover{box-shadow:0 0; color:#fff; margin-top:7px}
</style>
<h4 class="d-flex justify-content-between align-items-center">
  <span>Sports Kit Requisition List</span>
  <a href="#" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-left-long"></i> Back
  </a>
</h4>

<div class=" bg-white shadow mb-5 p-2 table-responsive table-container">
		<table class="table table-bordered bg-white table-hover">
				<thead>
					<tr class="custom-header-row">
						<th>Sr. No.</th>
						<th>Application Id</th>
						<th>Body Type</th>
                        <!--<th>Name Of Designation</th>-->						
						<th>District</th>
						<th>Name Of Municipal Body<br>/ Gram Panchayat/ Ward/ Village</th>
						<th>Sports</th>
						<th>Equipment</th>
						<th>Quantity</th>
						<!--<th>Availability Of FoP/Hall/Poles</th>
						<th>Tentative Players</th>
						<th>Date Of Last Issued Sports</th>>
						<th>Action</th-->
						<!--th>Download <br>Application PDF</th-->
						<th>Application Status</th>
					</tr>
				</thead>
				<tbody>
				@foreach($sportsRequests as $index => $request)
                
				<tr>
					<td>{{ $index + 1 }}.</td>
					<td>
					<div  class="d-flex"><button type="button" data-bs-toggle="modal" data-bs-target="#modal{{ $request->id }}" class="bg-transparent text-primary app-id-view-btn">{{ $request->applicant_id }} </button>
						@if($request->gram_municipal_signed_document)
							<a href="{{ url('uploads/gram_municipal_signed_document/' . basename($request->gram_municipal_signed_document)) }}" target="_blank" class="btn btn-primary">
								<i class="fa-solid fa-file-arrow-down"></i> 
							</a>
						@else
							<button class="btn btn-secondary w-100" disabled>
								No Document Available
							</button>
						@endif
						
						</div>
						<div class="modal fade" id="modal{{ $request->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-xl modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-muted" id="exampleModalLabel">Application Id : {{ $request->applicant_id }}</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										
										<style>
											.badge-custom { font-size: 0.9rem; padding: 0.6rem 1rem; }
											.card-custom {border-radius: 1rem;box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);padding: 1rem 1.5rem;background-color: #ffffff;margin-bottom: 1.5rem;}
											.info-label {font-size: 0.85rem;font-weight: 500;color: #6c757d;}
											h5 {font-weight: 550;font-size: 0.9rem;}
										</style>
										
										<div class="container">
											<div class="card card-custom">
												<div class="row">
													<!-- Application Submitted Date -->
													<div class="col-12 col-md-4">
														<small class="info-label text-muted">Application Submitted Date</small>
														<h5 class="mt-1">{{ \Carbon\Carbon::parse($request->created_at)->format('d M Y') }}</h5>
													</div>

													<!-- Application ID -->
													<div class="col-12 col-md-4">
														<!--<small class="info-label text-muted">Application Id</small>
														<h5 class="mt-1">{{ $request->applicant_id }}</h5>-->
													</div>


													<!-- Application Status -->
													<div class="col-12 col-md-4">
														<small class="info-label text-muted">Application Status</small>
														<h5 class="mt-1">
															  <strong>
																@if(($request->status == 'Approved') && empty($request->disbursement_status))
																  <span class="badge rounded-pill bg-success badge-custom"><i class="fa-solid fa-thumbs-up me-1"></i> Approved</span>
															  @elseif(($request->status == 'Approved') && !empty($request->disbursement_status) )
																<span class="badge rounded-pill bg-success"><i class="fa-solid fa-thumbs-up"></i> Kit Disbursed</span>
																@elseif($request->status == 'Rejected')
																  <span class="badge rounded-pill bg-danger badge-custom"><i class="fa-solid fa-ban me-1"></i> Rejected</span>
																@elseif($request->status == 'Verified')
																  <span class="badge rounded-pill bg-primary badge-custom"><i class="fa-solid fa-check me-1"></i> Verified</span>
																@elseif($request->status == 'Not Verified')
																  <span class="badge rounded-pill bg-warning text-dark badge-custom"><i class="fa-solid fa-xmark me-1"></i> Not Verified</span>
																@else
																  <form action="{{ route('dso.verify', $request->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to Verify this?');">
																	@csrf
																	<button type="submit" class="btn btn-success btn-sm me-2"> <i class="fa-solid fa-check"></i> Verify </button>
																  </form>
																  <button type="button" class="btn btn-danger btn-sm" onclick="document.getElementById('rejection-remarks').classList.remove('d-none'); this.classList.add('d-none');">
																	<i class="fa-solid fa-xmark"></i> Not Verify
																  </button>
																@endif
															  </strong>
															</h5>
													</div>
													
													<!-- Rejection Remarks (Initially Hidden) -->
													<div class="col-12 d-none" id="rejection-remarks">
														<label class="form-label">Not Verify Remarks</label>
														<form action="{{ route('dso.not_verify', $request->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to Not Verify this?');">
															@csrf
															<div class="d-flex flex-column flex-md-row gap-2">
																<textarea class="form-control" name="not_verify_remark" rows="2" placeholder="Enter reason..." required></textarea>
																<button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Submit</button>
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>



										<!-- <div class="row">												
											<div class="col-sm-12 col-sm-6 col-md-3 mb-3">
												<small class="info-label text-muted">1. Name of Applicant</small>
												<h5>{{ $request->name }}</h5>
											</div>
											<div class="col-sm-12 col-sm-6 col-md-3 mb-3">
												<small class="info-label text-muted">2. District </small>
												<h5>{{ $request->district }}</h5>
											</div>
											<div class="col-sm-12 col-sm-6 col-md-3 mb-3">
												<small class="info-label text-muted">6. Body Type</small>
												<h5>{{ $request->designation === 'gram' ? 'Gram Panchayat' : 'Municipal Body' }}</h5>
											</div>
											<div class="col-sm-12 col-sm-6 col-md-3 mb-3">
												<small class="info-label text-muted">4. Name Of Designation </small>
												<h5>{{ $request->specific_designation }}</h5>
											</div>
											<div class="col-sm-12 col-sm-6 col-md-3 mb-3">
												<small class="info-label text-muted">5. Name Of Municipal Body<br>/ Gram Panchayat/ Ward/ Village </small>
												<h5>{{ $request->area_name }}</h5>
											</div>
											
										</div> -->
										
										<!-- Continue inside your container or card -->
										<div class="container my-4">
											<div class="card card-custom mt-4">
												<div class="row g-4">

													<!-- Name of Applicant -->
													<div class="col-12 col-md-3">
														<small class="info-label text-muted">1. Name of Applicant</small>
														<h5 class="mt-1">{{ $request->name }}</h5>
													</div>

													<!-- District -->
													<div class="col-12 col-md-3">
														<small class="info-label text-muted">2. District</small>
														<h5 class="mt-1">{{ $request->district }}</h5>
													</div>

													<!-- Body Type -->
													<div class="col-12 col-md-3">
														<small class="info-label text-muted">6. Body Type</small>
														<h5 class="mt-1">
										{{ $request->designation === 'gram' ? 'Gram Panchayat' : 'Municipal Body' }}
										</h5>
													</div>

													<!-- Name Of Designation -->
													<div class="col-12 col-md-3">
														<small class="info-label text-muted">4. Name Of Designation</small>
														<h5 class="mt-1">{{ $request->specific_designation }}</h5>
													</div>

													<!-- Name Of Area -->
													<div class="col-12 col-md-6">
														<small class="info-label text-muted">
										5. Name Of Municipal Body / Gram Panchayat / Ward / Village
										</small>
														<h5 class="mt-1">{{ $request->area_name }}</h5>
													</div>

												</div>
											</div>
										</div>
										
										
										<div class="container my-4">
											<div class="card card-custom mt-4">
												<div class="row">
													<div class="col-12 games-authorised-sec">
														<h4 class="text-dark mb-2 border-bottom">Games Kit Applied</h4> @php $equipmentList = json_decode($request->sports_equipment); @endphp @if(is_array($equipmentList) && count($equipmentList))
														<div class="row">
															<div class="col-md-2"><h6>Game</h6></div>
															<div class="col-md-2"><h6>Equipment</h6></div>
															<div class="col-md-1"><h6>Qty</h6></div>
															<div class="col-md-2"><h6>FoP/Hall/Poles</h6></div>
															<div class="col-md-1"><h6>Players</h6></div>
															<div class="col-md-2"><h6>Last Issued</h6></div>
															<div class="col-md-2"><h6>Vendor</h6></div>
														</div>
														@foreach($equipmentList as $equipment)
														<div class="row">
															<div class="col-md-2">
																<p>{{ $equipment->name ?? 'N/A' }}</p>
															</div>
															<div class="col-md-2">
																<p>{{ $equipment->equipment ?? 'N/A' }}</p>
															</div>
															<div class="col-md-1">
																<p>{{ $equipment->quantity ?? '0' }}</p>
															</div>
															<div class="col-md-2">
																<p>{{ $equipment->fop_available ?? 'N/A' }}</p>
															</div>
															<div class="col-md-1">
																<p>{{ $equipment->players_count ?? 'N/A' }}</p>
															</div>
															<div class="col-md-2">
																@if(!empty($equipment->last_issued_date))
																<p>{{ \Carbon\Carbon::parse($equipment->last_issued_date)->format('d-m-Y') }}</p>
																@else
																<p>N/A</p>
																@endif
															</div>
															<div class="col-md-2">
																<p>

																	@php $assigned = \App\Models\EquipmentVendorAssignment::where('request_id', $request->id) ->where('equipment_name', $equipment->name) ->first(); @endphp @if($assigned) {{-- Show assigned vendor name --}} @php $assignedVendor = \App\Models\Vendor::find($assigned->vendor_id);
																	@endphp
																	<strong>Assigned To:</strong>
																	<br />
																	<span class="badge bg-success">
														{{ $assignedVendor->vendor_name ?? 'Vendor Not Found' }}
													</span> @else @endif
																</p>
															</div>
														</div>
														
												@endforeach
												@else
												<p>No kit applied.</p>
												@endif
														
													</div>
												</div>
											</div>
										</div>

							    	</div>
							    </div>
							</div>
						</div>
					</td>
					<td>{{ $request->designation === 'gram' ? 'Gram Panchayat' : 'Municipal Body' }}</td>
					<!--<td>{{ $request->specific_designation }}</td>-->
					<td>{{ $request->district }}</td>
					<td>{{ $request->area_name }}</td>

					{{-- Sports --}}
					<td>
						@php $equipmentList = json_decode($request->sports_equipment); @endphp
						@if(is_array($equipmentList))
							<ul class="list-unstyled mb-0 ps-2">
								@foreach($equipmentList as $equipment)
									<li><strong>{{ $equipment->name ?? 'N/A' }}</strong></li>
								@endforeach
							</ul>
						@else
							<span>N/A</span>
						@endif
					</td>

					{{-- Equipment --}}
					<td>
						@if(is_array($equipmentList))
							<ul class="list-unstyled mb-0 ps-2">
								@foreach($equipmentList as $equipment)
									<li>{{ $equipment->equipment ?? 'N/A' }}</li>
								@endforeach
							</ul>
						@else
							<span>N/A</span>
						@endif
					</td>

					{{-- Quantity --}}
					<td>
						@if(is_array($equipmentList))
							<ul class="list-unstyled mb-0 ps-2">
								@foreach($equipmentList as $equipment)
									<li>{{ $equipment->quantity ?? '0' }}</li>
								@endforeach
							</ul>
						@else
							<span>N/A</span>
						@endif
					</td>

					
					<!--td>
						
						
					</td-->

					<!--<td>
						@if($request->status == 'Pending')
							<form action="{{ route('dso.verify', $request->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to Verify this?');">
								@csrf
								<button type="submit" class="btn btn-success w-100 mb-2">
							Verify
						</button>
							</form>

							<form action="{{ route('dso.not_verify', $request->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to Not Verify this?');">
								@csrf
								<button type="submit" class="btn btn-danger w-100">
									Not Verify
								</button>
							</form>
						@endif
					</td>-->
					
					
					<td>
						<strong>
							@if(($request->status == 'Verified') && empty($request->disbursement_status) )
								<span class="badge rounded-pill bg-success w-100"><i class="fa-solid fa-thumbs-up"></i> Verified</span> <br />
								<!--@if($request->disbursement_status != 'Completed')-->
								<a href="#" class="badge bg-primary w-100" data-bs-toggle="modal" data-bs-target="#requestDisclosure{{ $request->applicant_id }}">Disburse Kit</a>
								
                                <div class="modal fade" id="requestDisclosure{{ $request->applicant_id }}" tabindex="-1" aria-labelledby="requestDisclosureLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
    		<div class="modal-header">
       			<h5 class="modal-title" id="requestDisclosureLabel">Disbursal Receipt </h5>
       			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
     		<div class="modal-body">
				<form action="{{ route('dso.kit-disbursement.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-3 mb-2">
            <label>Date of Issue</label>
            <h6>{{ \Carbon\Carbon::now()->format('d F Y') }}</h6>
            <input type="hidden" name="issue_date" value="{{ \Carbon\Carbon::now()->toDateTimeString() }}">
        </div>
		<input type="hidden" name="request_id" value="{{ $request->id }}">
        <div class="col-4 mb-2">
            <label>Firm Name</label>
            <h6>{{ $request->vendor_name }}</h6>
           
			<input type="text" class="form-control" name="firm_name" required />
        </div>
        <div class="col-3 mb-2">
            <label>Name of the Owner</label>
            <h6>{{ $request->owner_name }}</h6>
           
			<input type="text" class="form-control" name="owner_name" required />
        </div>
		 
        <div class="col mb-2">
            <label>Mobile Number</label>
            <h6>{{ $request->mob }}</h6>
        
			<input type="text" class="form-control" name="mobile_number" required />
        </div>
    </div>
    <hr class="mt-0" />
    <div class="row">
        <div class="col">
            <label><strong>Source of Fund</strong></label><br />
            <div class="form-check">
                <input class="form-check-input" type="radio" name="fund_source" value="DSE" id="fundSourceDSE">
                <label class="form-check-label" for="fundSourceDSE">
                    Sourced by DSC 
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="fund_source" value="HQ" id="fundSourceHQ" checked>
                <label class="form-check-label" for="fundSourceHQ">
                    Sourced by HQ
                </label>
            </div>
        </div>
        <div class="col">
            <label><strong>Amount of procurement</strong></label>
            <input type="text" class="form-control" name="procurement_amount" required />
        </div>
        <div class="col">
            <label><strong>Bill no. and Voucher no.</strong></label>
            <input type="text" class="form-control mb-2" name="bill_no" required />
            <input type="file" class="form-control" name="voucher_file" accept=".pdf,.jpg,.jpeg,.png" required />
        </div>
    </div>

    <div class="text-end mt-3">
        <button type="submit" class="btn btn-success">Disburse Kit</button>
    </div>
</form>
       
      		</div>
     		<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<!--<button type="button" class="btn btn-success">Disburse Kit</button>-->
     		</div>
   		</div>
 	</div>
</div>
                                <!--@endif-->
							@elseif(($request->status == 'Verified') && !empty($request->disbursement_status) )
								<span class="badge rounded-pill bg-success w-100"><i class="fa-solid fa-thumbs-up"></i> Kit Disbursed</span>
							@elseif($request->status == 'Rejected')
								<span class="badge rounded-pill bg-danger w-100"><i class="fa-solid fa-ban"></i> Rejected</span>
							@elseif($request->status == 'Verified')
								<span class="badge rounded-pill bg-primary w-100"><i class="fa-solid fa-check"></i> Verified</span>
							@elseif($request->status == 'Not Verified')
								<span class="badge rounded-pill bg-warning w-100"><i class="fa-solid fa-xmark"></i> Not Verified</span>
							@else
								<span class="badge rounded-pill bg-primary w-100"><i class="fa-solid fa-check"></i> In-Progress</span>
								  <!--<form action="{{ route('dso.verify', $request->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to Verify this?');">
									@csrf
									<button type="submit"  class="btn btn-success w-100 mb-2">
								 Verify
							</button>
								</form>

								
<button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectRemarkModal{{ $request->id }}">
  Not Verify
</button>-->

<!-- Modal -->
<div class="modal fade" id="rejectRemarkModal{{ $request->id }}" tabindex="-1" aria-labelledby="rejectRemarkModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
     <form action="{{ route('dso.not_verify', $request->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to Not Verify this?');">
        @csrf
        <div class="modal-body">
          <h5>Not Verify Remarks</h5>
          <textarea class="form-control" name="not_verify_remark" required></textarea>
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
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
							
		
</div>			
		

   
	 
    @endsection
	<script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function (e) {
                    $('#img-upload').attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp").change(function(){
            readURL(this);
        }); 
		
		$(document).ready(function(){
			$('.navbar-toggler').click(function(){
				$('aside').toggleClass('main');
			});
		
		});
			
	</script>