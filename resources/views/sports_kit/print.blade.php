@extends('gm_main')
<style>
 /* Hide print-only content on screen */
    .print-only {display: none;}
	@page{orientation: A4;margin:0; padding:0}		
	div p{margin:15px 0 10px;}
    @media print {
		* {-webkit-print-color-adjust: exact !important; color-adjust: exact !important;print-color-adjust: exact !important;}
       .print-only {display: block !important;}
		.container{max-width:100% !important}
        .btn, 
        .no-print, .content-area header{
            display: none !important;
        }
		.row {display:flex;}
		.col-md-4{width: 33% !important;  }
		
    }
</style>


@section('content')
<section>
<div class="container mt-4">
			<h4 class="">Equipment Request Form  </h4>
			
			<div class=" bg-white shadow mb-5">
			<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <form action="{{ route('sports_kit.uploadform') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2 no-print">
        @csrf
        <input type="file" name="signed_document" accept="application/pdf,image/*" class="form-control form-control-sm" required />
        <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-print me-1"></i> Upload Signed Form</button>
    </form>
    <button class="btn btn-light btn-sm no-print" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

				<div class="row justify-content-between py-1 pt-4 border-bottom align-items-center">
					<div class="col-12 px-5 py-2">
						
						<div class="row request-default-info">	
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label>1. Name of Applicant</label>
								<h5>{{ $kit->name }}</h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label>2. Designation of Applicant </label>
								<h5>{{ $kit->designation === 'gram' ? 'Gram Panchayat' : 'Municipal Body' }} </h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label>3. Name of Municipal Body/Gram Panchayat</label>
								<h5>{{ $kit->block }} </h5>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 mb-3">
								<label>4. District</label>
								<h5>{{ $kit->district }}</h5>
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
										<th>Tentative number of Players</th>
										<th>Last Date of issued</th>
										<th>Whether FoP/Hall/Poles are available for mentioned Sports?</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									@php
    $equipmentList = is_string($kit->sports_equipment)
        ? json_decode($kit->sports_equipment, true)
        : $kit->sports_equipment;
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
            
            
				<td>{{ $item['players_count'] ?? 'N/A' }}</td>
				<td>{{ $item['last_issued_date'] ?? 'N/A' }}</td>
				<td>{{ $item['fop_available'] ?? 'N/A' }}</td>
                <td>{{ !empty($kit->disbursement_status) ? 'Ready for Disbursement' : 'In-Progress' }}</td>
            </tr>
        @endforeach

															
								</tbody>
							</table>
							
						</div>
						<div class=" print-only">
											<!-- Terms & Conditions -->
												<div class="row mt-2">
													<div class="col-md-12">
														<div class="form-check d-flex align-items-center justify-content-between p-0">
															<p>?? Agree to Terms &amp; Conditions</p>                                          
														</div>
													</div>
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <h6 class="text-danger">Terms & Conditions</h6>
                                <ul>
                                    <li>Only the sports equipment mentioned in the scheme shall be given.</li>
                                    <li>Municipal Bodies/ Gram Panchayats are eligible to apply for sports equipment during a two-year period.</li>
                                    <li>Equipment for Wrestling and Judo will be provided for only one of the two sports.</li>
                                    <li>All issued sports equipment must be registered in the official record of the Municipal Body/ Gram Panchayat.</li>
                                </ul>
                            </div>
                        </div>
													<div style="page-break-after:always"></div>
                        
                        <div class="col-12">
    <div class="alert alert-danger declaration-area">
        <h6 class="text-danger">Declaration by Applicant</h6>
        <p>
            It is certified that proper Field of Play (FoP) for the requisite sports is available, and I have not received any sports items during the last two financial years. The photographs attached with the application are the latest. All the above particulars given by me are true and correct. Nothing has been concealed by me. False information or concealment of material information in the application form by me will render me ineligible in the future for said scheme and may invite penal consequences.
        </p>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="declaration_place" class="form-label"><strong>Place:</strong></label>
                <input type="text" class="form-control" id="declaration_place" name="declaration_place" placeholder="Enter Place" value="{{ $kit->area_name }}" required>
            </div>
            <div class="col-md-4 mb-3">
                
            </div>
            <div class="col-md-4 mb-3">
                <label for="declaration_signature" class="form-label"><strong>Signature of Applicant Official Stamp (if applicable)</strong></label>
                <input type="text" class="form-control" id="declaration_signature" name="declaration_signature" placeholder="Enter Full Name" value="{{ $kit->name }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="declaration_date" class="form-label"><strong>Dated:</strong></label>
                <input type="date" class="form-control" id="declaration_date" name="declaration_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" 
            readonly required>
            </div>
        </div>

        
    </div>
</div>
<hr />
<div class="col-12">
    <div class="alert alert-danger declaration-area">
        <h6 class="text-danger">Declaration by Applicant</h6>
        <p>
            All the above particulars given by me are true and correct. Nothing has been concealed by me. False information or concealment of material information in the registration form by me will render me ineligible in future for said scheme and department is free to take appropriate action as deemed suitable against me in this regard.
        </p>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="declaration_place" class="form-label"><strong>Place:</strong></label>
                <input type="text" class="form-control" id="declaration_place" name="declaration_place" placeholder="Enter Place" value="{{ $kit->area_name }}" required>
            </div>
            <div class="col-md-4 mb-3">
                
            </div>
            <div class="col-md-4 mb-3">
                <label for="declaration_signature" class="form-label"><strong>Signature of Applicant Official Stamp (if applicable)</strong></label>
                <input type="text" class="form-control" id="declaration_signature" name="declaration_signature" placeholder="Enter Full Name" value="{{ $kit->name }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="declaration_date" class="form-label"><strong>Dated:</strong></label>
                <input type="date" class="form-control" id="declaration_date" name="declaration_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" 
            readonly required>
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
</section>
@endsection



