@extends('layouts.dso_main')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<h4 class="">Sports Kit Requisition List <a href="" class="btn btn-secondary float-end"><i class="fa-solid fa-arrow-left-long"></i> Back</a></h4>
<div class=" bg-white shadow mb-5 p-2">
			<table class="table table-bordered bg-white table-hover">
				<thead>
					<tr class="bg-primary text-white">
						<th>Sr. No.</th>
						<th>Designation</th>
						<th>District</th>
						<th>Sports Request Details</th>
						<th>Availability Of FoP/Hall/Poles</th>
						<th>Tentative Players</th>
						<th>Date Of Last Issued Sports</th>
						<th>Application Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@foreach($sportsRequests as $index => $request)
				<tr>
					<td>{{ $index + 1 }}.</td>
					<td>{{ $request->designation }}</td>
					<td>{{ $request->district }}</td>
					<td>
						@php
							$equipmentList = json_decode($request->sports_equipment, true);
						@endphp
						@if(is_array($equipmentList))
							<ul>
								@foreach($equipmentList as $equipment)
									<li>{{ $equipment['name'] }} - {{ $equipment['equipment'] }} (Qty: {{ $equipment['quantity'] }})</li>
								@endforeach
							</ul>
						@else
							<span>No equipment data</span>
						@endif
           	 		</td>
            
					<td>{{ $request->fop_available }}</td>
					<td>{{ $request->players_count }}</td>
					<td>
						{{ $request->last_issued_date ? date('d-m-Y', strtotime($request->last_issued_date)) : 'N/A' }}
					</td>
					<td>
						<strong>
							@if($request->status == 'Approved')
								<span class="badge rounded-pill bg-success w-100"><i class="fa-solid fa-thumbs-up"></i> Approved</span> <br /><a href="#" class="btn btn-primary w-100 h-100" data-bs-toggle="modal" data-bs-target="#requestDisclosure">Request for disclosure</a>
							@elseif($request->status == 'Rejected')
								<span class="badge rounded-pill bg-danger w-100"><i class="fa-solid fa-ban"></i> Rejected</span>
							@elseif($request->status == 'Verified')
								<span class="badge rounded-pill bg-primary w-100"><i class="fa-solid fa-check"></i> Verified</span>
							@elseif($request->status == 'Not Verified')
								<span class="badge rounded-pill bg-warning w-100"><i class="fa-solid fa-xmark"></i> Not Verified</span>
							@else
								<span class="badge rounded-pill bg-info w-100"><i class="fa-solid fa-hourglass-half"></i> Pending</span>
							@endif
						</strong>
					</td>

					<td>
						@if($request->status == 'Pending')
							<form action="{{ route('dso.verify', $request->id) }}" method="POST" style="display:inline;">
								@csrf
								<button type="submit" class="btn btn-success w-100 mb-2">
							Verify
						</button>
							</form>

							<form action="{{ route('dso.not_verify', $request->id) }}" method="POST" style="display:inline;">
								@csrf
								<button type="submit" class="btn btn-danger w-100">
									Not Verify
								</button>
							</form>
						@endif
					</td>
				</tr>
				@endforeach
				</tbody>
			</table>
								
		
</div>			
				
<div class="modal fade" id="requestDisclosure" tabindex="-1" aria-labelledby="requestDisclosureLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
    		<div class="modal-header">
       			<h5 class="modal-title" id="requestDisclosureLabel">Request Closure: Disbursal Receipt </h5>
       			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
     		<div class="modal-body">
				<form>
					<div  class="row">
						<div class="col-3 mb-2">
							<label>Date of Issue</label>
							<h6>{{ \Carbon\Carbon::now()->format('d F Y h:ia') }}</h6>
						</div>
						<div class="col-4 mb-2">
							<label>Firm Name</label>
							<h6>Game Zone</h6>
						</div>
						<div class="col-3 mb-2">
							<label>Name of the Owner</label>
							<h6>Akshay Mehta</h6>
						</div>
						<div class="col mb-2">
							<label>Mobile Number</label>
							<h6>9876543451</h6>
						</div>
					</div>
					<hr class="mt-0" />
					<div class="row">
						<div class="col">
							<label><strong>Source of Fund</label></strong><br />
							<div class="form-check">
								<input class="form-check-input" type="radio" name="fundSource" id="fundSourceDSE">
								<label class="form-check-label" for="fundSourceDSE">
									Sourced by DSE 
								</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="fundSource" id="fundSourceHQ" checked>
								<label class="form-check-label" for="fundSourceHQ">
									Sourced by HQ
								</label>
							</div>
						</div>
						<div class="col">
							<label><strong>Amount of procurement</strong></label>
							<input type="text" class="form-control" />
						</div>
						<div class="col">
							<label><strong>Bill no. and Voucher no. </strong></label>
							<input type="text" class="form-control mb-2" />
							<input type="file" class="form-control" />
						</div>
					</div>

				</form>       
      		</div>
     		<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
     		</div>
   		</div>
 	</div>
</div>
            
     <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header bg-success">
					<h5 class="modal-title" id="exampleModalLabel">Sports Equipments Center</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 col-sm-6 col-md-3">
							<label>1. Name of the Owner</label>
							<h6>Navjot Kaur</h6>
						</div>
						<div class="col-sm-12 col-sm-6 col-md-3">
							<label>2. PAN No. </label>
							<h6>123Jdf123</h6>
						</div>
						<div class="col-sm-12 col-sm-6 col-md-6">
							<label>3. Firm Address</label>
							<h6>Sco. 109-110, Sector 17B, Chandigarh</h6>
						</div>
						<div class="col-sm-12 col-sm-6 col-md-3">
							<label>4. District</label>
							<h6>Chandigarh</h6>
						</div>
						<div class="col-sm-12 col-sm-6 col-md-3">
							<label>5. Pincode</label>
							<h6>160017</h6>
						</div>
						<div class="col-12 mt-3 games-authorised-sec">
							<p>Games Kit Authorised</p>
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-3">
									<div class="card">
										<div class="card-body text-center">
											<h4>Volleyball</h4>
											<p>₹ 300 (per unit)</p>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<div class="card">
										<div class="card-body text-center">
											<h4>Basketball</h4>
											<p>₹ 500 (per unit)</p>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<div class="card">
										<div class="card-body text-center">
											<h4>Handball</h4>
											<p>₹ 300 (per unit)</p>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<div class="card">
										<div class="card-body text-center">
											<h4>Cricket</h4>
											<p>₹ 300 (per unit)</p>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<div class="card">
										<div class="card-body text-center">
											<h4>Judo</h4>
											<p>₹ 300 (per unit)</p>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<div class="card">
										<div class="card-body text-center">
											<h4>Wrestling</h4>
											<p>₹ 300 (per unit)</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
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