@extends('gm_main')
<style>
@page {
  size: A4;
  margin: 0;padding:0
}
@media print {
	
	html,body{padding:0;margin:0; -webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;}
  .download-doc-area,  #downloadButton,  #uploadbutton, .content-area header {
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
 <form action="{{ url('/sports-kit/store') }}" method="POST" enctype="multipart/form-data" class=" py-2">
                    @csrf

 <!-- Static Information -->
                    <div class="row mb-4">                                
                        <div class="col-xs-12 col-sm-4 col-md-3 mb-3">
    <div>
        <label>Name of Head Person</label>
        <input type="text" class="form-control" name="name"
            value="{{ old('name', session('first_form_data.name') ?? $userDetail->full_name_en ?? '') }}" readonly required>
    </div> 
</div>
						<div class="col-xs-12 col-sm-4 col-md-3 mb-3">
    <div>
        <label>District</label>
        <input type="text" class="form-control" name="district"
            value="{{ old('district', session('first_form_data.district') ?? $userDetail->district ?? '') }}" required>
    </div> 
</div>
						<div class="col-xs-12 col-sm-4 col-md-3 mb-3">
    <div>
        <label>Block</label>
        <input type="text" class="form-control" name="block"
            value="{{ old('block', session('first_form_data.block') ?? $userDetail->block_town ?? '') }}" required>
    </div> 
</div>
						<div class="col-xs-12 col-sm-4 col-md-3 mb-3">
    <div>
        <label>Area Name</label>
        <input type="text" class="form-control" name="area_name"
            value="{{ old('area_name', session('first_form_data.area_name') ?? $userDetail->ward_village ?? '') }}" required>
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
                        
                        
                        
                        

                    </div>
					<hr />
					
    
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

<div id="second_form" >

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
               
                    <!-- Static Information -->
                   
					
					

                    <!-- Sports Kit Requisition -->
                    <div class="mb-3">
                    <h4 class="mb-3 text-dark">Sports Request Details 
                    <button type="button" class="btn btn-sm btn-success float-end" onclick="addEquipment()">+ Add More</button>
                </h4>
                    <div class="title-area d-flex">
                        <p class="col mb-0 px-1">Select Sports</h6>
                        <p class="col mb-0 px-1">Select Equipements</h6>
                        <p class="col mb-0 px-1">Enter Quantity</h6>
                        <p class="col-3 mb-0 px-1">Whether FoP/Hall/Poles are available for mentioned Sports?</h6>
                        <p class="col-3 mb-0 px-1">Tentative Number of Players</h6>
                        <p class="col-3 mb-0 px-1">Date of Last Issued Sports Item/Equipment</h6>
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
            <select name="sports_equipment[0][fop_available]" class="form-control" required>
               <option value="" selected>Select Availability</option>
                <option value="Yes" >Yes</option>
                <option value="No" >No</option>
            </select>
        </div>
		<div class="col mb-0 px-1">
            <input type="number" name="sports_equipment[0][players_count]" class="form-control" placeholder="Players count"  required>
        </div>
		<div class="col mb-0 px-1">
            <input type="date" class="form-control" name="sports_equipment[0][last_issued_date]" id="last_issued_date" max="{{ date('Y-m-d') }}" required>
        </div>
		<div class="col-3 mb-0 px-1">
            <input type="file" class="form-control" name="sports_equipment[0][photo]" accept="image/*" />
        </div>
        <!--<div class="col mb-0 px-1" style="display:none">
            <input type="date" class="form-control" name="sports_equipment[0][date]" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"/>
        </div>-->
        <div class="col-1 mb-0 px-1 text-end">
            <!--button type="button" class="btn btn-danger" onclick="removeEquipment(this)"><i class="fa-solid fa-trash"></i></button-->
        </div>
    </div>
</div>

                    <!-- Availability of Facilities -->
                    <!--<div class="row mt-3">
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
                    </div>-->

                    

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
            It is certified that proper Field of Play (FoP) for the requisite sports is available, and I have not received any sports items during the last two financial years. The photographs attached with the application are the latest. All the above particulars given by me are true and correct. Nothing has been concealed by me. False information or concealment of material information in the application form by me will render me ineligible in the future for said scheme and may invite penal consequences.
        </p>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="declaration_place" class="form-label"><strong>Place:</strong></label>
                <input type="text" class="form-control" id="declaration_place" name="declaration_place" placeholder="Enter Place" value="{{ $userDetail->ward_village }}" required>
            </div>
            <div class="col-md-4 mb-3">
                
            </div>
            <div class="col-md-4 mb-3">
                <label for="declaration_signature" class="form-label"><strong>Signature of Applicant Official Stamp (if applicable)</strong></label>
                <input type="text" class="form-control" id="declaration_signature" name="declaration_signature" placeholder="Enter Full Name" value="{{ $userDetail->full_name_en }}" required>
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
                <input type="text" class="form-control" id="declaration_place" name="declaration_place" placeholder="Enter Place" value="{{ $userDetail->ward_village }}" required>
            </div>
            <div class="col-md-4 mb-3">
                
            </div>
            <div class="col-md-4 mb-3">
                <label for="declaration_signature" class="form-label"><strong>Signature of Applicant Official Stamp (if applicable)</strong></label>
                <input type="text" class="form-control" id="declaration_signature" name="declaration_signature" placeholder="Enter Full Name" value="{{ $userDetail->full_name_en }}" required>
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

                    <!-- Form Submission -->
                    <hr />
                    <div class="row mb-3">                    
                        <div class="col-12 text-end">
                            <!--<button type="reset" class="btn btn-secondary" id="downloadButton" onclick="window.print()">Save and Download</button>-->
                            <button type="submit" id="uploadbutton" class="btn btn-primary">Save</button>
                        </div>
                    </div>

                </form>
				
            </div>    
            </div>    
        </div>    
    </div>            
</div>
</div>

@endsection
<script src="{{ url('assets/js/jquery.min.js') }}"></script>
<!--<script>
    document.addEventListener("DOMContentLoaded", function() {
        if ("{{ session('show_second_form') }}") {
            const secondForm = document.getElementById('second_form');
            if (secondForm) {
                secondForm.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
</script>-->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const designationType = document.getElementById('designation_type');
    const specificDesignation = document.getElementById('specific_designation');

    const options = {
        gram: [
            { value: 'Sarpanch', text: 'Sarpanch' },
            { value: 'Gram Sachiv', text: 'Gram Sachiv' }
        ],
        municipal: [
            { value: 'Councillor', text: 'Councillor' }
        ]
    };

    const sessionDesignation = "{{ session('first_form_data.designation') }}";
    const sessionSpecificDesignation = "{{ session('first_form_data.specific_designation') }}";

    // Set initial designation type
    if (sessionDesignation) {
        designationType.value = sessionDesignation;
    }

    // Populate options based on selected type
    function populateSpecificOptions(type) {
        specificDesignation.innerHTML = ''; // Clear old options

        if (options[type]) {
            options[type].forEach(function (opt) {
                const option = document.createElement('option');
                option.value = opt.value;
                option.textContent = opt.text;

                if (opt.value === sessionSpecificDesignation) {
                    option.selected = true;
                }

                specificDesignation.appendChild(option);
            });
        }
    }

    populateSpecificOptions(designationType.value);

    designationType.addEventListener('change', function () {
        populateSpecificOptions(this.value);
    });
});
</script>


<script>
$(document).ready(function(){
	$('#uploadbutton').click(function(){
		$('.upload-signed-file-area').css("display", "flex");		
	});
});

    // Define equipment options and their max quantity per sport
const equipmentLimits = {
    "Volleyball": { "Balls": 6, "Net": 1 },
    "Football": { "Balls": 6, "Net": 1 },
    "Basketball": { "Balls": 6 },
    "Handball": { "Balls": 6, "Net": 1 },
    "Boxing": { "Punching Bags": 6, "Gloves": 12 },
    "Wrestling": { "Mats [1 mtr x 2 mtr x5 cm]": 18 },
    "Judo": { "Mats [1 mtr x 2 mtr x5 cm]": 18 },
    "Cricket": {
        "Bats": 2,
        "Set of Wickets": 2,
        "Cricket Balls": 6,
        "Batting Pads": 2,
        "Batting Gloves": 2,
        "Wicket Keeping Pads": 1,
        "Wicket Keeping Gloves": 1
    }
};

// Function to update the Equipment dropdown based on selected sport
function updateEquipmentOptions(sportSelect) {
    let parentDiv = sportSelect.closest('.d-flex'); 
    if (!parentDiv) return;

    let equipmentSelect = parentDiv.querySelector('select[name*="[equipment]"]');
    let quantityInput = parentDiv.querySelector('input[name*="[quantity]"]');
    
    if (!equipmentSelect || !quantityInput) return;

    let selectedSport = sportSelect.value;
    
    // Get all selected sports
    let selectedSports = Array.from(document.querySelectorAll('select[name*="[name]"]'))
        .map(select => select.value);

    // Enforce Wrestling/Judo condition
    if (selectedSports.includes("Wrestling") && selectedSports.includes("Judo")) {
        alert("You can only request equipment for either Wrestling or Judo, not both.");
        sportSelect.value = ""; // Reset selection
        return;
    }

    equipmentSelect.innerHTML = '<option value="" selected disabled>Select Equipment</option>'; 

    if (selectedSport in equipmentLimits) {
        Object.keys(equipmentLimits[selectedSport]).forEach(equipment => {
            let option = document.createElement("option");
            option.value = equipment;
            option.textContent = equipment;
            equipmentSelect.appendChild(option);
        });
    }

    // Reset equipment & quantity fields
    equipmentSelect.value = "";
    quantityInput.value = "";
    quantityInput.removeAttribute("max");
}


// Function to enforce quantity limits
function updateQuantityLimit(equipmentSelect) {
    let parentDiv = equipmentSelect.closest('.d-flex');
    if (!parentDiv) return;

    let sportSelect = parentDiv.querySelector('select[name*="[name]"]');
    let quantityInput = parentDiv.querySelector('input[name*="[quantity]"]');

    if (!sportSelect || !quantityInput) return;

    let selectedSport = sportSelect.value;
    let selectedEquipment = equipmentSelect.value;

    if (selectedSport in equipmentLimits && selectedEquipment in equipmentLimits[selectedSport]) {
        let maxQuantity = equipmentLimits[selectedSport][selectedEquipment];
        quantityInput.setAttribute("max", maxQuantity);
        quantityInput.setAttribute("min", 1);
        quantityInput.value = maxQuantity; // 🔥 This line pre-fills max quantity

        quantityInput.addEventListener("input", function () {
            if (parseInt(this.value) > maxQuantity) {
                alert(`Maximum quantity for ${selectedEquipment} in ${selectedSport} is ${maxQuantity}.`);
                this.value = maxQuantity;
            }
        });
    } else {
        quantityInput.removeAttribute("max");
        quantityInput.value = "";
    }
}


// Function to add a new Equipment row
function addEquipment() {
    let list = document.getElementById('equipment-list');
    let count = document.querySelectorAll('#equipment-list .d-flex').length;

    // Get all current selections (Sport + Equipment)
    let selectedCombinations = Array.from(document.querySelectorAll('#equipment-list .d-flex')).map(row => {
        let sport = row.querySelector('select[name*="[name]"]')?.value;
        let equipment = row.querySelector('select[name*="[equipment]"]')?.value;
        return sport && equipment ? `${sport}-${equipment}` : null;
    }).filter(Boolean);

    // Prevent adding both Wrestling and Judo
    const selectedSports = selectedCombinations.map(c => c.split('-')[0]);
    if (selectedSports.includes("Wrestling") && selectedSports.includes("Judo")) {
        alert("You can only request equipment for either Wrestling or Judo, not both.");
        return;
    }

    // Create new row
    let newItem = document.createElement('div');
    newItem.classList.add('d-flex', 'mb-2');
    newItem.innerHTML = `
        <div class="col mb-0 px-1">
            <select name="sports_equipment[${count}][name]" class="form-control" required onchange="updateEquipmentOptions(this)">
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
            <select name="sports_equipment[${count}][equipment]" class="form-control" required onchange="checkDuplicate(this); updateQuantityLimit(this)">
                <option value="" selected disabled>Select Equipment</option>
            </select>
        </div>
        <div class="col mb-0 px-1">
            <input type="number" name="sports_equipment[${count}][quantity]" class="form-control" placeholder="Quantity" required min="1" readonly>
        </div>
		<div class="col mb-0 px-1">
            <select name="sports_equipment[${count}][fop_available]" class="form-control" required>
               <option value="" selected>Select Availability</option>
                <option value="Yes" >Yes</option>
                <option value="No" >No</option>
            </select>
        </div>
		<div class="col mb-0 px-1">
            <input type="number" name="sports_equipment[${count}][players_count]" class="form-control" placeholder="Players count" required min="1">
        </div>
		<div class="col mb-0 px-1">
             <input type="date" class="form-control" name="sports_equipment[${count}][last_issued_date]" class="form-control" max="{{ date('Y-m-d') }}" required>
        </div>
        <div class="col-3 mb-0 px-1">
            <input type="file" name="sports_equipment[${count}][photo]" class="form-control" accept="image/*">
        </div>
        <!--<div class="col mb-0 px-1" style="display:none">
            <input type="date" name="sports_photos[${count}][date]" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
        </div>-->
        <div class="col-1 mb-0 px-1 text-end">
            <button type="button" class="btn btn-danger" onclick="removeEquipment(this)">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>`;

    list.appendChild(newItem);
}


// Function to remove equipment row
function removeEquipment(button) {
    button.closest('.d-flex').remove();
}

// Attach event listeners on page load
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('select[name*="[name]"]').forEach(select => {
        select.addEventListener('change', function () {
            updateEquipmentOptions(this);
        });
    });

    document.querySelectorAll('select[name*="[equipment]"]').forEach(select => {
        select.addEventListener('change', function () {
            updateQuantityLimit(this);
        });
    });

    document.querySelectorAll('input[name*="[quantity]"]').forEach(input => {
        input.addEventListener('input', function () {
            updateQuantityLimit(this);
        });
    });
});

function checkDuplicate(equipmentSelect) {
    const parentRow = equipmentSelect.closest('.d-flex');
    const selectedSport = parentRow.querySelector('select[name*="[name]"]').value;
    const selectedEquipment = equipmentSelect.value;

    const currentCombo = `${selectedSport}-${selectedEquipment}`;

    const allCombos = Array.from(document.querySelectorAll('#equipment-list .d-flex')).map(row => {
        if (row === parentRow) return null; // skip current row
        const sport = row.querySelector('select[name*="[name]"]')?.value;
        const equipment = row.querySelector('select[name*="[equipment]"]')?.value;
        return sport && equipment ? `${sport}-${equipment}` : null;
    }).filter(Boolean);

    if (allCombos.includes(currentCombo)) {
        alert(`You already selected ${selectedSport} with ${selectedEquipment}. Please choose a different combination.`);
        equipmentSelect.value = '';
    }
}


</script>



