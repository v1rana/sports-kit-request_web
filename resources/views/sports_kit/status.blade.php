@extends('gm_main')

@section('content')
<section>
<div class="container mt-4">
			<h4 class="">Equipment Requested Status  </h4>
			<!--<div class=" bg-white shadow mb-5">
				<div class="row">
					<div class="col-12 px-5 py-2">
						<h5 class="text-dark">Application Status</h5>
						<div class="progress-track">
							<ul id="progressbar">
								<li class="step0 {{ in_array($application->status, ['Pending','Verified', 'Not Verified', 'Approved', 'Rejected', 'Disbursed']) ? 'active' : '' }}" id="step1"><span>1. Application at DSO</span></li>
								<li class="step0 {{ in_array($application->status, ['Approved', 'Rejected', 'Disbursed']) ? 'active' : '' }}" id="step2"><span>2. Application at ADC</span></li>
								<li class="step0 {{ in_array($application->status, ['Approved', 'Disbursed']) ? 'active' : '' }}" id="step3"><span>3. Application at HQ</span></li>
								<li class="step0" id="step4"><span>4. Vendor is assigned</span></li>
								<li class="step0 text-right" id="step5"><span>5. Equipment disbursed </span></li>
							</ul>
						</div>
					</div>
				</div>
			</div>-->
			<div class=" bg-white shadow mb-5">
				<div class="row justify-content-between py-1 pt-4 border-bottom align-items-center">
					<div class="col-12 px-5 py-2">
						
						<div class="row request-default-info">	
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label>1. Name of head person</label>
								<h5>Rajender Singh</h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label>2. Designation </label>
								<h5>{{ $application->designation }} </h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label>3. Block</label>
								<h5>{{ $application->block }} </h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label>4. District</label>
								<h5>{{ $application->district }}</h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label >Tentative number of Players </label>
								<h5>{{ $application->players_count }}</h5>
								
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label>Last Date of issued</label>
								<h5>{{ $application->last_issued_date }}</h5>
								
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 mb-3">
								<label>Whether FoP/Hall/Poles are available for mentioned Sports?</label>
								<h5>{{ $application->fop_available }}</h5>
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
        <img src="{{ url('/' . $item['photo']) }}" width="50" height="50" class="border"/>
    </td>
    <td>{{ \Carbon\Carbon::parse($application->created_at)->format('d M Y, h:i A') }}</td>
	<td>{{ $application->vendor_id != '' ? 'Ready for Disbursement' : 'Pending' }}</td>
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



