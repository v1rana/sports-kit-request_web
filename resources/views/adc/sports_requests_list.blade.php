@extends('adc_main')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
					<h4 class="">Sports Kit Requisition List <a href="" class="btn btn-secondary float-end"><i class="fa-solid fa-arrow-left-long"></i> Back</a></h4>
					<div class=" bg-white shadow mb-5">
						<div class="row justify-content-between border-bottom align-items-center">
							<div class="col-12">
								<table class="table table-bordered bg-white table-hover">
									<thead>
										<tr class="bg-primary text-white">
											<th>Sr. No.</th>
											<th>Name</th>
											<th>Designation</th>
                                            <th>Block</th>
											<th>District</th>
                                            <th>Area Name</th>
											<th>Sports Request Details</th>
											<th>Availability Of FoP/Hall/Poles</th>
                                            <th>Tentative Players</th>
                                            <th>Date Of Last Issued Sports</th>
                                            <th>Status</th>
                                            <th>Action</th>
										</tr>
									</thead>
									<tbody>
                                    @foreach($sportsRequests as $index => $request)
                    <tr>
                        <td>{{ $index + 1 }}.</td>
                        <td></td>
                        <td>{{ $request['designation'] }}</td>
                        <td>{{ $request['block'] }}</td>
                        <td>{{ $request['district'] }}</td>
                        <td>{{ $request['area_name'] }}</td>
                        
                        <td>
                            @php
                                $equipmentList = json_decode($request['sports_equipment'], true);
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
                        <td>{{ $request['fop_available'] }}</td>
                        <td>{{ $request['players_count'] }}</td>
                        <td>{{ date('d-m-Y', strtotime($request['last_issued_date'])) }}</td>
                        
<td>
    @if($request->status == 'Approved')
        <span class="badge bg-success">Approved</span>
    @elseif($request->status == 'Rejected')
        <span class="badge bg-danger">Rejected</span>
    @else
        <span class="badge bg-warning">Verified</span>
    @endif
</td>
<td>
    @if($request->status == 'Verified')
        <form action="{{ route('adc.approve', $request->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" style="width:160px;" class="btn btn-success">
                Approve
            </button>
        </form>

        <form action="{{ route('adc.reject', $request->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" style="width:160px;" class="btn btn-danger">
                Reject
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