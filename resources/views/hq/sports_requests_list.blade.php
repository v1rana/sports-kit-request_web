@extends('hq_main')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<h4 class="">Sports Kit Requisition List <a href="" class="btn btn-secondary float-end"><i class="fa-solid fa-arrow-left-long"></i> Back</a></h4>
<div class=" bg-white shadow mb-5 p-3">
	<div class="table-responsive">
		<table class="table table-bordered bg-white table-hover">
			<thead>
				<tr class="bg-primary text-white">
					<th>Sr. No.</th>
					<th>Designation</th>
					<th>Block</th>
					<th>District</th>
					<th>Area Name</th>
					<th>Sports</th>
					<th>Equipemnt</th>
					<th>Quantity</th>
					<th>Availability Of FoP/Hall/Poles</th>
					<th>Tentative Players</th>
					<th>Date Of Last Issued Sports</th>
					<th>Application Status</th>
					<!--<th width="160px">Assign Vendor</th>-->
				</tr>
			</thead>
			<tbody>
				@foreach($sportsRequests as $index => $request)
				<tr>
    <td>{{ $index + 1 }}.</td>
    <td>{{ $request['designation'] }}</td>
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

    {{-- Availability of FoP/Hall/Poles --}}
    <td>
        @if(is_array($equipmentList))
            <ul class="list-unstyled mb-0">
                @foreach($equipmentList as $equipment)
                    <li>{{ $equipment['fop_available'] ?? 'N/A' }}</li>
                @endforeach
            </ul>
        @else
            <span>N/A</span>
        @endif
    </td>

    {{-- Tentative Players --}}
    <td>
        @if(is_array($equipmentList))
            <ul class="list-unstyled mb-0">
                @foreach($equipmentList as $equipment)
                    <li>{{ $equipment['players_count'] ?? 'N/A' }}</li>
                @endforeach
            </ul>
        @else
            <span>N/A</span>
        @endif
    </td>

    {{-- Last Issued Date --}}
    <td>
        @if(is_array($equipmentList))
            <ul class="list-unstyled mb-0">
                @foreach($equipmentList as $equipment)
                    <li>
                        @if(!empty($equipment['last_issued_date']))
                            {{ \Carbon\Carbon::parse($equipment['last_issued_date'])->format('d-m-Y') }}
                        @else
                            N/A
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <span>N/A</span>
        @endif
    </td>

    {{-- Assign Vendor --}}
    <td>
        @if(is_array($equipmentList))
    <ul class="list-unstyled mb-0">
        @foreach($equipmentList as $index => $equipment)
            @php
                $assigned = \App\Models\EquipmentVendorAssignment::where('request_id', $request->id)
                    ->where('equipment_name', $equipment['name'])
                    ->first();
            @endphp
            <li class="mb-2">
                <form action="{{ route('hq.assignvendor') }}" method="POST">
                    @csrf
                    <input type="hidden" name="request_id" value="{{ $request->id }}">
                    <input type="hidden" name="equipment_name" value="{{ $equipment['name'] }}">

                    <strong>
                        {{ $equipment['name'] }} - {{ $equipment['equipment'] }} (Qty: {{ $equipment['quantity'] }})
                    </strong>

                    <select name="vendor_id" class="form-select form-select-sm mt-1 mb-1" required>
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}"
                                {{ $assigned && $assigned->vendor_id == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->vendor_name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-sm btn-primary w-100">Assign</button>
                </form>
            </li>
        @endforeach
    </ul>
@else
    <span>No equipment data</span>
@endif
    </td>
</tr>

				@endforeach
			</tbody>
		</table>
			
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