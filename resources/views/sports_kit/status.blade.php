@extends('gm_main')

@section('content')
<section>
<div class="container mt-4">
			<h4 class="">Haryana Provision of Sports Equipment Scheme 2025-2026  </h4>
			
			<div class=" bg-white shadow mb-5">
				<div class="row justify-content-between py-1 pt-4 border-bottom align-items-center">
					<div class="col-12 px-5 py-2">
							@if(isset($successMessage))
    <div class="alert alert-success">
        {{ $successMessage }}
    </div>
@endif
						<div class="row request-default-info">	
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label>1. Name of Applicant</label>
								<h5>{{ $userDetail->full_name_en }}</h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label>2. Designation of Applicant</label>
								<h5>{{ $application->designation === 'gram' ? 'Gram Panchayat' : 'Municipal Body' }} </h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label>3. Name of Municipal Body/Gram Panchayat</label>
								<h5>{{ $application->block }} </h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label>4. District</label>
								<h5>{{ $application->district }}</h5>
							</div>
							
						</div>
						<div class="row request-default-info">										
							<h6 class="mt-2 p-0 text-dark">Sports Kit Requisition </h6>
							<table class="table table-striped table-bordered">
								<thead class="bg-dark text-white">
									<tr>
										<th>Sr. No.</th>
										<th>Sports Name</th>
										<th>Equipment</th>
										<th>Quantity</th>
										<th>Location Img</th>
										<th>Date & Time</th>
										<th>Tentative number of Players</th>
										<th>Last Date of issued</th>
										<th>Whether FoP/Hall/Poles are available for mentioned Sports?</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									@php
    $equipmentList = json_decode($application->sports_equipment, true);
@endphp

@foreach ($equipmentList as $index => $item)
<tr>
                <td>{{ $index + 1 }}.</td>
                <td>{{ $item['name'] ?? 'N/A' }}</td>
            
                <td>{{ $item['equipment'] ?? 'N/A' }}</td>
            
                <td>{{ $item['quantity'] ?? '0' }}</td>
           
                <td>
                    <img src="{{ url('/' . $item['photo']) }}" width="80" height="80" class="border" />
                </td>
            
                <td>{{ \Carbon\Carbon::parse($application->created_at)->format('d M Y, h:i A') }}</td>
            
				<td>{{ $item['players_count'] ?? 'N/A' }}</td>
				<td>{{ $item['last_issued_date'] ?? 'N/A' }}</td>
				<td>{{ $item['fop_available'] ?? 'N/A' }}</td>
                <td>{{ $application->disbursement_status != '' ? 'Ready for Disbursement' : 'In-Progress' }}</td>
            </tr>
@endforeach

															
								</tbody>
							</table>
							
						</div>
						<div class="row mt-3 request-default-info">
							
						</div>
						
					</div>	
				</div>	
			</div>			
		</div>
</section>
@endsection



