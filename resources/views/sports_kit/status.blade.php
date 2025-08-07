@extends('gm_main')

@section('content')
<style>
	.label-bold {
    font-size: 16px!important;
    font-weight: 600!important;
}

.value-text {
    font-size: 15px!important;
    font-weight: 600!important;
}
</style>
<section>
<div class="container mt-4">
			<h4>Haryana Provision of Sports Equipment Scheme 2025-2026</h4>

			<div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
				
				<h4 style="color:white">Application ID : {{ $application->applicant_id }}</h4>
			</div>
			
			
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
								<label class="label-bold">1. Name of Applicant</label>
								<h5 class="value-text">{{ $userDetail->full_name_en }}</h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label class="label-bold">2. Body Type</label>
								<h5 class="value-text">{{ $application->designation === 'gram' ? 'Gram Panchayat' : 'Municipal Body' }} </h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label class="label-bold">3. Designation Of Applicant</label>
								<h5 class="value-text">{{ $application->specific_designation }} </h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label class="label-bold">4. District</label>
								<h5 class="value-text">{{ $application->district }}</h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6 mb-3">
								<label class="label-bold">5. Name of Municipal Body/ Gram Panchayat/ Ward/ Village</label>
								<h5 class="value-text">{{ $application->area_name }}</h5>
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
                <td>
    @if(($application->status == 'Approved') && empty($application->disbursement_status))
        <span class="badge rounded-pill bg-success w-100"><i class="fa-solid fa-thumbs-up"></i> Approved</span> <br />
    @elseif(($application->status == 'Approved') && !empty($application->disbursement_status))
        <span class="badge rounded-pill bg-success w-100"><i class="fa-solid fa-thumbs-up"></i> Disbursed</span>
    @elseif($application->status == 'Rejected')
        <span class="badge rounded-pill bg-danger w-100"><i class="fa-solid fa-ban"></i> Rejected</span>
    @elseif($application->status == 'Verified')
        <span class="badge rounded-pill bg-primary w-100"><i class="fa-solid fa-check"></i> Verified</span>
    @elseif($application->status == 'Not Verified')
        <span class="badge rounded-pill bg-warning w-100"><i class="fa-solid fa-xmark"></i> Not Verified</span>
    @else
        <span class="badge rounded-pill bg-primary w-100"><i class="fa-solid fa-check"></i> In-Progress</span>
    @endif
</td>
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



