@extends('gm_main')
<style>
    /* Hide print-only content on screen */
        .print-only {display: none;}
    	@page{orientation: A4;margin:0; padding:20px}
		.alert ul{padding:0;}
		.alert ul li{list-style:none}
    	//div p{margin:15px 0 10px;}
        @media print {
    		* {-webkit-print-color-adjust: exact !important; color-adjust: exact !important;print-color-adjust: exact !important;}
           .print-only {display: block !important;}
    		.container{max-width:100% !important}
            .btn, 
            .no-print, .content-area header, .card-header, header{
                display: none !important;
            }
    		.row {display:flex;}
    		.col-md-4{width: 33% !important;  }
    		h5{font-size:17px;}
			table .table img{width: 55px !important; height: 55px !important}
			table label{font-size:13px;}
        }
</style>


@section('content')
<section>
    <div class="container ">
        <h4 class="no-print">Registration Form  </h4>

        <div class=" bg-white shadow mb-5">
            <div class="no-print ">
				<div class="card-header  bg-primary text-white d-flex justify-content-between align-items-center">
					<form action="{{ route('sports_kit.uploadform') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2 no-print m-0 align-items-center">
						@csrf
						<input type="file" name="signed_document" accept="application/pdf,image/*" class="form-control form-control-sm no-print" required />
						<button type="submit" class="btn btn-success btn-sm w-75"><i class="fa-solid fa-print me-1"></i> Upload Signed Form</button>
					</form>
					<button class="btn btn-light btn-sm no-print" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
				</div>
			</div>
			
			<table width="100%">
				<thead class="" style="display: table-header-group;">
					<tr>
						<th>
							<table width="100%" style="background: #225395;" class="print-only">
								<tr>
									<td style="padding: 15px;">
										<div class="logo">
											<a href="#" title="Go to home" class="site_logo" rel="home">
												<img class="" id="logo" src="http://3.108.161.6/assets/images/logo-sports.png" alt="Sports Haryana Govt">
												<div class="logo_text">
													<strong lang="">खेल विभाग हरियाणा</strong>
													<h1 class="h1-logo">Sports Department , Government of Haryana</h1>
													<span class="logo-sub-title">Let the young minds grow to the full potential</span>
												</div>
											</a>
										</div>
									</td>
									<td align="right" style="padding: 15px;">
										<img src="http://3.108.161.6/assets/images/DigitalIndia.png" alt="Sports Haryana Govt" style="filter:invert(1); width: 100%;max-width:120px">
									</td>
								</tr>
							</table>							
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td style="padding: 10px 15px;" colspan="4">
										<h4 class="print-only">Registration Form  </h4>
									</td>
								</tr>
								<tr>
									<td style="padding: 10px 15px;">
										<label>1. Name of Applicant</label>
										<h5>{{ $kit->name }}</h5>
									</td>
									<td style="padding: 10px 15px;">
										<label>2. Body Type</label>
										<h5>{{ $kit->designation === 'gram' ? 'Gram Panchayat' : 'Municipal Body' }} </h5>
									</td>
									<td style="padding: 10px 15px;">
										<label>3. Designation Of Applicant</label>
										<h5>{{ $kit->specific_designation }} </h5>
									</td>
									<td style="padding: 10px 15px;">
										<label>4. District</label>
										<h5>{{ $kit->district }}</h5>
									</td>
								</tr>
								<tr>
									<td style="padding: 10px 15px;" colspan="4">
										<label>5. Name of Municipal Body/ Gram Panchayat/ Ward/ Village</label>
										<h5>{{ $kit->area_name }} </h5>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="print-only" style="padding: 15px;">
							<div class="alert alert-secondary declaration-area">
								<h6 class="text-dark">Declaration by Applicant (Self-Declaration)</h6>
								<p>
									All the above particulars given by me are true and correct. Nothing has been concealed by me. False information or concealment of material information in the registration form by me will render me ineligible in future for said scheme and department is
									free to take appropriate action as deemed suitable against me in this regard.
								</p>

								<div class="row justify-content-between align-items-end">
									<div class="col-md-4 mb-3">
										<div class="row">
											<div class="col-12">
												<label for="declaration_place" class="form-label"><strong>Place:</strong></label>
												<h6 class="text-dark">{{ $kit->area_name }}</h6>
											</div>
											<div class="col-12">
												<label for="declaration_date" class="form-label"><strong>Dated:</strong></label>
												<h6 class="text-dark">{{ \Carbon\Carbon::now()->format('Y-m-d') }}</h6>
											</div>
										</div>
									</div>
									
									<div class="col-md-4 mb-3">
									
										<p style="border-bottom:1px dotted; width: 200px;margin-top: 20px;"></p>
										<label for="declaration_signature" class="form-label"><strong>Signature of Applicant Official Stamp (if applicable)</strong></label>
										<!--h5>{{ $kit->name }}</h5-->
										
									</div>
								</div>
							</div>
						</td>
					</tr>
					
					<tr>
						<td style="page-break-after:always;padding-bottom: 30px"></td>
					</tr>
					<tr>
						<td style="padding: 15px 15px 0">
							<h4 class="mt-2 p-0 text-dark">Sports Kit Requisition </h4>
							<table class="table table-striped table-bordered" style="width:100%;">
								<thead class="bg-dark text-white">
									<tr>
										<th width="60px">Sr. No.</th>
										<th>Sports Name</th>
										<th>Equipment</th>
										<th width="68px">Quantity</th>
										<th>Location Img</th>
										<th>Tentative number of Players</th>
										<th>Last Date of issued</th>
										<th>Whether FoP/Hall/Poles are available for mentioned Sports?</th>
										<th width="100px">Status</th>
									</tr>
								</thead>
								<tbody>
									@php $equipmentList = is_string($kit->sports_equipment) ? json_decode($kit->sports_equipment, true) : $kit->sports_equipment; @endphp @foreach ($equipmentList as $index => $item)

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

						</td>
					</tr>
					<tr>
						<td style="padding:15px 15px 5px;" class="print-only">
							<div class="col-12">
                                <div class="alert alert-success">
                                    <h6 class="text-success">Terms & Conditions</h6>
                                    <ul>
                                        <li>✅ Only the sports equipment mentioned in the scheme shall be given.</li>
                                        <li>✅ Municipal Bodies/ Gram Panchayats are eligible to apply for sports equipment during a two-year period.</li>
                                        <li>✅ Equipment for Wrestling and Judo will be provided for only one of the two sports.</li>
                                        <li>✅ All issued sports equipment must be registered in the official record of the Municipal Body/ Gram Panchayat.</li>
                                    </ul>
                                </div>
                            </div>
						</td>
					</tr>
					<tr>
						<td style="padding:5px 15px 15px;" class="print-only">
							<div class="alert alert-secondary declaration-area">
								<h6 class="text-dark">Declaration by Applicant (Field of Play)</h6>
								<p>
									It is certified that proper Field of Play (FoP) for the requisite sports is available, and I have not received any sports items during the last two financial years. The photographs attached with the application are the latest. All the above particulars
									given by me are true and correct. Nothing has been concealed by me. False information or concealment of material information in the application form by me will render me ineligible in the future for said scheme
									and may invite penal consequences.
								</p>

								<div class="row justify-content-between align-items-end">
									<div class="col-md-4 mb-3">
										<div class="row">
											<div class="col-12">
												<label for="declaration_place" class="form-label"><strong>Place:</strong></label>
												<h6 class="text-dark">{{ $kit->area_name }}</h6>
											</div>
											<div class="col-12">
												<label for="declaration_date" class="form-label"><strong>Dated:</strong></label>
												<h6 class="text-dark">{{ \Carbon\Carbon::now()->format('Y-m-d') }}</h6>
											</div>
										</div>
									</div>
									
									<div class="col-md-4 mb-3">
									
										<p style="border-bottom:1px dotted; width: 200px;margin-top: 20px;"></p>
										<label for="declaration_signature" class="form-label"><strong>Signature of Applicant Official Stamp (if applicable)</strong></label>
										<!--h5>{{ $kit->name }}</h5-->
										
									</div>
								</div>


							</div>
						</td>
					</tr>
					
				</tbody>
			</table>
			
			
			
            <div class="row justify-content-between py-1 border-bottom align-items-center">
                <div class="col-12 px-5 py-2">

                  

                </div>
            </div>
        </div>
    </div>
</section>
@endsection