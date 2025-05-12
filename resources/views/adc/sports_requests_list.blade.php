@extends('adc_main')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<style>
	    label.info-label,small.info-label {font-weight: 500;font-size:14px;display: block; margin-bottom:0; line-height:normal}
	    .modal-body h5{margin:0}
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
</style>
<h4 class="">Sports Kit Requisition List <a href="" class="btn btn-secondary float-end"><i class="fa-solid fa-arrow-left-long"></i> Back</a></h4>
<div class=" bg-white shadow mb-5 p-2">
			<table class="table table-bordered bg-white table-hover">
				<thead>
					<tr class="bg-primary text-white">
						<th>Sr. No.</th>
						<th>Application Id</th>
						<th>Designation</th>
						<th>Block</th>
						<th>District</th>
						<th>Area Name</th>
						<th>Sports</th>
						<th>Equipemnt</th>
						<th>Quantity</th>
						<!--<th>Availability Of FoP/Hall/Poles</th>
						<th>Tentative Players</th>
						<th>Date Of Last Issued Sports</th>-->
						<th>Action</th>
						<th>Download PDF</th>
						<th>Application Status</th>
					</tr>
				</thead>
				<tbody>
			@foreach($sportsRequests as $index => $request)
			<tr>
				 <td>{{ $index + 1 }}.</td>
					 <td>{{ $request['applicant_id'] }}</td>
    <td>{{ $request->designation === 'gram' ? 'Gram Panchayat' : 'Municipal Body' }}</td>
    <td>{{ $request['block'] }}</td>
    <td>{{ $request['district'] }}</td>
    <td>{{ $request['area_name'] }}</td>

    {{-- Sports --}}
    <td>
        @php $equipmentList = json_decode($request['sports_equipment'], true); @endphp
        @if(is_array($equipmentList))
            <ul class="list-unstyled mb-0">
                @foreach($equipmentList as $equipment)
                    <li><strong>{{ $equipment['name'] ?? 'N/A' }}</strong></li>
                @endforeach
            </ul>
        @else
            <span>N/A</span>
        @endif
    </td>

    {{-- Equipment --}}
    <td>
        @if(is_array($equipmentList))
            <ul class="list-unstyled mb-0">
                @foreach($equipmentList as $equipment)
                    <li>{{ $equipment['equipment'] ?? 'N/A' }}</li>
                @endforeach
            </ul>
        @else
            <span>N/A</span>
        @endif
    </td>

    {{-- Quantity --}}
    <td>
        @if(is_array($equipmentList))
            <ul class="list-unstyled mb-0">
                @foreach($equipmentList as $equipment)
                    <li>{{ $equipment['quantity'] ?? '0' }}</li>
                @endforeach
            </ul>
        @else
            <span>N/A</span>
        @endif
    </td>

   
				
				<td>
						<button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modal{{ $request->id }}"> View</button>
						<div class="modal fade" id="modal{{ $request->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-xl modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header bg-success">
										<h5 class="modal-title" id="exampleModalLabel">Sports Equipments Center</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<div class="row">									
											<div class="col-sm-12 col-sm-6 col-md-4">
												<small class="info-label text-muted">Application Submitted Date</small>
												<h5>  {{ \Carbon\Carbon::parse($request->created_at)->format('d M Y, h:i A') }}</h5>
											</div>

											<div class="col-sm-12 col-sm-6 col-md-3">
												<small class="info-label text-muted">Application Id</small>
												<h5>{{ $request['applicant_id'] }}</h5>
											</div>

											<div class="col-sm-12 col-sm-6 col-md-3">
												<small class="info-label text-muted">Application Status</small>
												<h5> <strong>
						                            @if($request->status == 'Approved')
															<span class="badge bg-success"><i class="fa-solid fa-thumbs-up"></i> Approved</span>
														@elseif($request->status == 'Rejected')
															<span class="badge bg-danger"><i class="fa-solid fa-ban"></i> Rejected</span>
														@else
														 <form action="{{ route('adc.approve', $request->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to Approve this?');">
																@csrf
																<button type="submit"  class="btn btn-success w-100 mb-2">
															 Approve
														</button>
															</form>
														<a href="javascript:void(0);" class="btn btn-danger" onclick="document.getElementById('rejection-remarks').classList.remove('d-none'); this.classList.add('d-none');"> Reject </a>
														@endif
													</strong>
												</h5>
											</div>
					
											<div class="col-sm-12 col-md-2 d-none" id="rejection-remarks">
												<label>Reject Remarks</label>
													<form action="{{ route('adc.reject', $request->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to Reject this?');">
													@csrf
													<div class="d-flex flex-column flex-md-row gap-2">
														<textarea class="form-control" name="reject_remark" rows="2" placeholder="Enter reason..." required></textarea>
														<button type="submit" class="btn btn-primary">Submit</button>
													</div>
												</form>
											</div>
										</div>

										<hr />

										<div class="row">												
											<div class="col-sm-12 col-sm-6 col-md-3 mb-3">
												<small class="info-label text-muted">1. Name</small>
												<h5>{{ $request['name'] }}</h5>
											</div>
											<div class="col-sm-12 col-sm-6 col-md-3 mb-3">
												<small class="info-label text-muted">2. District </small>
												<h5>{{ $request['district'] }}</h5>
											</div>
											<div class="col-sm-12 col-sm-6 col-md-3 mb-3">
												<small class="info-label text-muted">3. District </small>
												<h5>{{ $request['district'] }}</h5>
											</div>
											<div class="col-sm-12 col-sm-6 col-md-3 mb-3">
												<small class="info-label text-muted">4. Block </small>
												<h5>{{ $request['block'] }}</h5>
											</div>
											<div class="col-sm-12 col-sm-6 col-md-3 mb-3">
												<small class="info-label text-muted">5. Area Name </small>
												<h5>{{ $request['area_name'] }}</h5>
											</div>
											<div class="col-sm-12 col-sm-6 col-md-3 mb-3">
												<small class="info-label text-muted">6. Designation</small>
												<h5>{{ $request->designation === 'gram' ? 'Gram Panchayat' : 'Municipal Body' }}</h5>
											</div>
										</div>

										<div class="row">
											<div class="col-12 mt-3 games-authorised-sec">
												<h4 class="text-dark mb-2 border-bottom">Games Kit Authorised</h4>
												@php $equipmentList = json_decode($request['sports_equipment'], true); @endphp
												@if(is_array($equipmentList) && count($equipmentList))
												<div class="row">
													<div class="col-md-2"><h6>Game</h6></div>
													<div class="col-md-2"><h6>Equipment</h6></div>
													<div class="col-md-1"><h6>Qty</h6></div>
													<div class="col-md-2"><h6>FoP/Hall/Poles</h6></div>
													<div class="col-md-1"><h6>Players</h6></div>
													<div class="col-md-2"><h6>Last Issued</h6></div>
												</div>
											 	@foreach($equipmentList as $equipment)
									            <div class="row">
									                <div class="col-md-2"><p>{{ $equipment['name'] ?? 'N/A' }}</p></div>
									                <div class="col-md-2"><p>{{ $equipment['equipment'] ?? 'N/A' }}</p></div>
									                <div class="col-md-1"><p>{{ $equipment['quantity'] ?? '0' }}</p></div>
									                <div class="col-md-2"><p>{{ $equipment['fop_available'] ?? 'N/A' }}</p></div>
									                <div class="col-md-1"><p>{{ $equipment['players_count'] ?? 'N/A' }}</p></div>
									                <div class="col-md-2">
									                    @if(!empty($equipment['last_issued_date']))
									                       <p> {{ \Carbon\Carbon::parse($equipment['last_issued_date'])->format('d-m-Y') }}</p>
									                    @else
									                        N/A
									                    @endif
									                </div>
									            </div>
									            @endforeach
									            @else
									            	<p>No kits authorized.</p>
								            	@endif
								            </div>
								        </div>
							    	</div>
							    </div>
							</div>
						</div>
					</td>
				<td>
						@if($request->gram_municipal_signed_document)
							<a href="{{ url('uploads/gram_municipal_signed_document/' . basename($request->gram_municipal_signed_document)) }}" target="_blank" class="btn btn-danger w-100">
								View PDF
							</a>
						@else
							<button class="btn btn-secondary w-100" disabled>
								No Document Available
							</button>
						@endif
					</td>
					<td>
					@if($request->status == 'Approved')
						<span class="badge bg-success"><i class="fa-solid fa-thumbs-up"></i> Approved</span>
					@elseif($request->status == 'Rejected')
						<span class="badge bg-danger"><i class="fa-solid fa-ban"></i> Rejected</span>
					@else
						<span class="badge rounded-pill bg-primary w-100"><i class="fa-solid fa-check"></i> Verified</span>
						 <!--<form action="{{ route('adc.approve', $request->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to Approve this?');">
									@csrf
									<button type="submit"  class="btn btn-success w-100 mb-2">
								 Approve
							</button>
								</form>

								
<button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectRemarkModal{{ $request->id }}">
  Reject
</button>-->

<!-- Modal -->
<div class="modal fade" id="rejectRemarkModal{{ $request->id }}" tabindex="-1" aria-labelledby="rejectRemarkModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
     <form action="{{ route('adc.reject', $request->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to Reject this?');">
        @csrf
        <div class="modal-body">
          <h5>Reject Remarks</h5>
          <textarea class="form-control" name="reject_remark" required></textarea>
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
				</td>
				
			</tr>
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