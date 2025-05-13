@extends('hq_main')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<h4 class="">Vendor List <a href="{{ route('hq.vendor') }}" class="btn btn-secondary float-end">Add vendor</a><a href="javascript:history.back()" class="btn btn-secondary float-end"><i class="fa-solid fa-arrow-left-long"></i> Back</a></h4>
<div class=" bg-white shadow mb-5 p-3">
	<div class="table-responsive">
		<table class="table table-bordered bg-white table-hover">
			<thead>
				<tr class="bg-primary text-white">
					<th>Sr. No.</th>
					<th>Vendor</th>
					<th>Owner</th>
					<th>Pan Of Owner</th>
					<th>Firm Address</th>
					<th>District</th>
					<th>Pincode</th>
					<th>Games(Rate Per kit)</th>
					<th>Vendor Added Date</th>
				</tr>
			</thead>
			<tbody>
				@foreach($vendors as $index => $vendor)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $vendor->vendor_name }}</td>
                        <td>{{ $vendor->owner_name }}</td>
                        <td>{{ $vendor->pan_of_owner }}</td>
                        <td>{{ $vendor->firm_address }}</td>
                        <td>{{ $vendor->district }}</td>
                        <td>{{ $vendor->pincode }}</td>
                        <td>
                            @foreach($vendor->sports as $sport)
							<div class="d-flex align-items-center mb-2">
								@if($sport->pivot->photo)
									<img src="{{ asset('uploads/games/' . $sport->pivot->photo) }}" width="40" height="40" class="me-2 rounded" />
								@endif
								<div>
									<strong>{{ $sport->sports_name }}</strong>
									<div class="text-muted small">₹{{ $sport->pivot->rate }}</div>
								</div>
							</div>
							@endforeach
						</td>
						<td>{{ date('d-m-Y', strtotime($vendor->created_at)) }}</td>
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
	