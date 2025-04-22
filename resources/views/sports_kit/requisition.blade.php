@extends('gm_main')
<style>
@page {
  size: A4;
  margin: 0;padding:0
}
@media print {
	
	html,body{padding:0;margin:0; -webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;}
  #second_form,.download-doc-area,  #downloadButton,  #uploadbutton, .content-area header {
    display: none !important;
  }
  label{font-size:13px !important;}
  .form-control, input{border:none !important; padding:0 !important; font-size:14px !important;background:transparent !important;}
  .container{max-width:100% !important;}
  select{appearance: none !important;
    -moz-appearance: none !important;
    -webkit-appearance: none !important;}
  header{border-bottom:1px solid #eee;}
   
}
</style>
@section('content')
<div class="container mt-4">

   <h4 class=""> Registration Form 
        <!--<a href="{{ url('/sports-kit') }}" class="btn btn-secondary float-end">
            <i class="fa-solid fa-arrow-left-long"></i> Back
        </a>-->
    </h4>
 <form action="{{ url('/sports-kit/uploadform') }}" method="POST" enctype="multipart/form-data" class=" py-2">
                    @csrf

 <!-- Static Information -->
                    <div class="row mb-4">                                
                        <div class="col-xs-12 col-sm-4 col-md-3 mb-3">
                            <div>
                                <label> Name of Head Person </label>
                                <input type="text" class="form-control" name="name" value="{{ session('first_form_data.name') }}" required>
                            </div> 
                        </div>
						<div class="col-xs-12 col-sm-4 col-md-3 mb-3">
                        <div>
                            <label>District</label>
                            <input type="text" class="form-control" name="district" value="{{ session('first_form_data.district') }}" required>
                            </div> 
                            </div>
						<div class="col-xs-12 col-sm-4 col-md-3 mb-3">
                        <div>
                            <label>Block</label>
                            <input type="text" class="form-control" name="block" value="{{ session('first_form_data.block') }}" required>
                            </div> 
						</div>
						<div class="col-xs-12 col-sm-4 col-md-3 mb-3">
							<div>
                            <label>Area Name</label>
                            <input type="text" class="form-control" name="area_name" placeholder="Enter Area Name" value="{{ session('first_form_data.area_name') }}" required>
                            </div> 
                            </div>
                        
                        <!--<div class="col-3 mb-3 px-0">
                        <div>
                            <label>2. Designation</label>
                            <input type="text" class="form-control" name="designation" value="" required>
                            </div> 
                            </div>-->
						
    <!-- Designation Type Dropdown -->
    <div class="col-xs-12 col-sm-4 col-md-3 mb-3">
        <label for="designation_type">Designation Type</label>
        <select class="form-control" name="designation" id="designation_type" required>
            <option value="">-- Select Type --</option>
            <option value="gram" {{ session('first_form_data.designation') == 'gram' ? 'selected' : '' }}>Gram Panchayat</option>
            <option value="municipal" {{ session('first_form_data.designation') == 'municipal' ? 'selected' : '' }}>Municipal Bodies</option>
        </select>
    </div>

    <!-- Specific Designation Dropdown -->
    <div class="col-xs-12 col-sm-4 col-md-3 mb-3">
        <label for="specific_designation">Specific Designation</label>
        <select class="form-control" name="specific_designation" id="specific_designation" required>
            <option value="">-- Select Designation --</option>
        </select>
    </div>
	
                        
                       
                    </div>
<!-- Terms & Conditions -->
                    <div class="row mt-3">
                        
                        
                        
                        <div class="col-12">
    <div class="alert alert-danger declaration-area">
        <h6 class="text-danger">Declaration by Applicant</h6>
        <p>
            All the above particulars given by me are true and correct. Nothing has been concealed by me. False information or concealment of material information in the registration form by me will render me ineligible in future for said scheme and department is free to take appropriate action as deemed suitable against me in this regard.
        </p>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="declaration_place" class="form-label"><strong>Place:</strong></label>
                <input type="text" class="form-control" id="declaration_place" name="declaration_place" placeholder="Enter Place" value="BABARWAS" required>
            </div>
            <div class="col-md-4 mb-3">
                
            </div>
            <div class="col-md-4 mb-3">
                <label for="declaration_signature" class="form-label"><strong>Signature of Applicant Official Stamp (if applicable)</strong></label>
                <input type="text" class="form-control" id="declaration_signature" name="declaration_signature" placeholder="Enter Full Name" value="Rajender singh" required>
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
					<hr />
					<div class="row mb-3 download-doc-area align-items-end justify-content-end">
						<div class="col-12 text-end">
							<button type="button" class="btn btn-success" id="downloadButton" onclick="window.print()">Submit and Download PDF</button>
							<input type="submit" class="btn btn-primary" id="uploadbutton" value="Upload Signed Form">
						</div>
						<div class="col-7 mt-3 text-end d-flex align-items-center justify-content-between">
							<label for="file_upload" class="form-label">Upload Signed Form</label>
							<input type="file" class="form-control mx-3" name="file" id="file_upload" required />
							<button type="button" class="btn btn-primary w-50">Upload File</button>
						</div>
					</div>
    
    <!-- Upload Button for the Signed Form -->
    <!--<hr />
    <div class="row mb-3">
        <div class="col-12">
            <label for="file_upload" class="form-label">Upload Signed Form</label>
            <input type="file" class="form-control" name="file" id="file_upload" required>
        </div>
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary" id="uploadbutton">Upload</button>
        </div>
    </div>-->
 </form>
<div id="second_form" style="display: {{ session('show_second_form') ? 'block' : 'none' }};">

    <h4 class=""> Kit Requisition Form 
        <!--<a href="{{ url('/sports-kit') }}" class="btn btn-secondary float-end">
            <i class="fa-solid fa-arrow-left-long"></i> Back
        </a>-->
    </h4>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
    <div class="bg-white shadow mb-5">
        <div class="row justify-content-between py-1 pt-4 border-bottom align-items-center w-100">
            <div class="col-12">
                <form action="{{ url('/sports-kit/store') }}" method="POST" enctype="multipart/form-data" class="px-5 py-2">
                    @csrf

                    <!-- Static Information -->
                    <div class="row request-default-info mb-4">                                
                        <div class="col-3 mb-3 pe-0">
                            <div>
                                <label>1. Name of Head Person </label>
                                <input type="text" class="form-control" name="name" value="Rajender singh" required>
                            </div> 
                        </div>
                        <div class="col-3 mb-3 px-0">
                        <div>
                            <label>2. Designation</label>
                            <input type="text" class="form-control" name="designation" value="Gram Sarpanch" required>
                            </div> 
                            </div>
                        <div class="col mb-3 px-0">
                        <div>
                            <label>3. Block</label>
                            <input type="text" class="form-control" name="block" value="KAIRU" required>
                            </div> 
                            </div>
                        <div class="col mb-3 px-0">
                        <div>
                            <label>4. District</label>
                            <input type="text" class="form-control" name="district" value="BHIWANI" required>
                            </div> 
                            </div>
                        <div class="col mb-3 ps-0">
                        <div>
                            <label>5. Area Name</label>
                            <input type="text" class="form-control" name="area_name" placeholder="Enter Area Name" value="BABARWAS" required>
                            </div> 
                            </div>
                    </div>
					
					

                    <!-- Sports Kit Requisition -->
                    <div class="mb-3">
                    <h4 class="mb-3 text-dark">Sports Request Details 
                    <button type="button" class="btn btn-sm btn-success float-end" onclick="addEquipment()">+ Add More</button>
                </h4>
                    <div class="title-area d-flex">
                        <p class="col mb-0 px-1">Select Sports</h6>
                        <p class="col mb-0 px-1">Select Equipements</h6>
                        <p class="col mb-0 px-1">Enter Quantity</h6>
                        <p class="col-3 mb-0 px-1">Select Location picture</h6>
                        <!--<p class="col mb-0 px-1">Select Date</h6>-->                        
                        <div class="col-1 mb-0 px-1"></div>
                    </div>
                    <div id="equipment-list">
                        <div class="d-flex mb-2">
                        <div class="col mb-0 px-1">
                            <select name="sports_equipment[0][name]" class="form-control" required onchange="updateEquipmentOptions(this)">
                                <option value="" selected disabled>Select Sport</option>
                                <option value="Volleyball">Volleyball</option>
                                <option value="Football">Football</option>
                                <option value="Basketball">Basketball</option>
                                <option value="Handball">Handball</option>
                                <option value="Boxing">Boxing</option>
                                <option value="Wrestling">Wrestling</option>
                                <option value="Judo">Judo</option>
                                <option value="Cricket">Cricket</option>
                            </select>
                        </div>
                        <div class="col mb-0 px-1">
    <select name="sports_equipment[0][equipment]" class="form-control" required onchange="updateQuantityLimit(this)">
                <option value="" selected disabled>Select Equipment</option>
            </select>
                        </div>
                        <div class="col mb-0 px-1">
                            <input type="number" name="sports_equipment[0][quantity]" class="form-control" placeholder="Quantity" readonly required>
                        </div>
                        <div class="col-3 mb-0 px-1">
                            <input type="file" class="form-control" name="sports_equipment[0][photo]" accept="image/*" />
                        </div>
                        <div class="col mb-0 px-1" style="display:none">
                            <input type="date" class="form-control" name="sports_equipment[0][date]" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"/>
                        </div>
                        <div class="col-1 mb-0 px-1 text-end">
                            <!--button type="button" class="btn btn-danger" onclick="removeEquipment(this)"><i class="fa-solid fa-trash"></i></button-->
                        </div>
                    </div>
                </div>

                    <!-- Availability of Facilities -->
                    <div class="row mt-3">
                    <div class="mb-3">
                    <label class="form-label">Whether FoP/Hall/Poles are available for mentioned Sports?</label>
                    <input type="radio" name="fop_available" value="Yes" required> Yes
                    <input type="radio" name="fop_available" value="No" required> No
                </div>
                        <div class="col-md-4 mb-3">
                            <label for="players_count" class="form-label">Tentative Number of Players</label>
                            <input type="number" class="form-control" name="players_count" id="players_count" min="1" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="last_issued_date" class="form-label">Date of Last Issued Sports Item/Equipment</label>
                            <input type="date" class="form-control" name="last_issued_date" id="last_issued_date" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    

                    <!-- Terms & Conditions -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-check d-flex align-items-center justify-content-between">
                                <span>
                                    <input class="form-check-input" type="checkbox" name="agree_terms" value="1" id="termsCondition" required>
                                    <label class="form-check-label" for="termsCondition">Agree to Terms & Conditions</label>
                                </span>                                            
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
                        
                        <div class="col-12">
    <div class="alert alert-danger declaration-area">
        <h6 class="text-danger">Declaration by Applicant</h6>
        <p>
            It is certified that proper Field of Play (FoP) for the requisite sports is available, and I have not received any sports items during the last two financial years. The photographs attached with the application are the latest. All the above particulars given by me are true and correct. Nothing has been concealed by me. False information or concealment of material informa