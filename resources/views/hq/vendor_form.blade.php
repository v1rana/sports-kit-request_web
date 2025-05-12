@extends('hq_main')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<h4 class="">Vendor <a href="javascript:history.back()" class="btn btn-secondary float-end"><i class="fa-solid fa-arrow-left-long"></i> Back</a></h4>
<div class="bg-white shadow mb-5">
    <div class="row justify-content-between py-1 pt-4 border-bottom align-items-center w-100">
        <div class="col-12">
            <!-- Vendor Form -->
            <h4 class="mb-3 text-dark">Add Vendor</h4>
            
            <form action="{{ route('vendor.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Firm Name -->
                    <div class="col-md-6 mb-3">
                        <label for="firm_name" class="form-label">Firm Name:</label>
                        <input type="text" class="form-control" id="firm_name" name="firm_name" required>
                    </div>

                    <!-- Owner Name -->
                    <div class="col-md-6 mb-3">
                        <label for="owner_name" class="form-label">Name of the Owner:</label>
                        <input type="text" class="form-control" id="owner_name" name="owner_name" required>
                    </div>

                    <!-- PAN No. of the Owner -->
                    <div class="col-md-6 mb-3">
                        <label for="pan_number" class="form-label">PAN No. of the Owner:</label>
                        <input type="text" class="form-control" id="pan_number" name="pan_number" required>
                    </div>
					 <div class="col-md-6 mb-3">
                        <label for="mob" class="form-label">Mobile:</label>
                        <input type="number" class="form-control" id="mob" name="mob" required>
                    </div>

                    <!-- Firm Address -->
                    <div class="col-md-6 mb-3">
                        <label for="firm_address" class="form-label">Firm Address:</label>
                        <textarea class="form-control" id="firm_address" name="firm_address" rows="3" required></textarea>
                    </div>

                    <!-- District -->
                    <div class="col-md-6 mb-3">
                        <label for="district" class="form-label">District:</label>
                        <input type="text" class="form-control" id="district" name="district" required>
                    </div>

                    <!-- Pincode -->
                    <div class="col-md-6 mb-3">
                        <label for="pincode" class="form-label">Pincode:</label>
                        <input type="text" class="form-control" id="pincode" name="pincode" required>
                    </div>

                    <!-- Games Kit Authorized -->
                    <h5 class="mt-4 mb-3">Games Kit Authorized</h5>
                    <div id="games-kit-list">
                        <div class="d-flex mb-3">
                            <!-- Game Selection -->
                            <div class="col-md-3 mb-3">
                                <label for="game" class="form-label">Game</label>
                                <select name="games[0][game]" class="form-control game-select" required onchange="updateEquipmentOptions(this)">
									<option value="">--Select Sports--</option>
                                    @foreach($sports as $sport)
										<option value="{{ $sport->id }}">{{ $sport->sports_name }}</option>

									@endforeach
                                </select>
                            </div>
							<div class="col mb-0 px-1">
								 <label for="game" class="form-label">Equipment</label>
								<select name="games[0][equipment]" class="form-control" required>
									<option value="" selected disabled>Select Equipment</option>
								</select>
							</div>
                            <!-- Rate Finalized -->
                            <div class="col-md-3 mb-3">
                                <label for="rate_finalized" class="form-label">Rate Finalized (per kit)</label>
                                <input type="number" class="form-control" name="games[0][rate]" required>
                            </div>

                            <!-- Picture -->
                            <div class="col-md-3 mb-3">
                                <label for="photo" class="form-label">Pic</label>
                                <input type="file" class="form-control" name="games[0][photo]" accept="image/*" required />
                            </div>

                            <div class="col-md-1 mb-3 text-end">
                                <button type="button" class="btn btn-sm btn-danger mt-4" onclick="removeGame(this)">Remove</button>
                            </div>
                        </div>
                    </div>

                    <!-- Button to Add More Games -->
                    <div class="col-12 mb-3">
                        <button type="button" class="btn btn-sm btn-success" onclick="addGame()">+ Add More Games</button>
                    </div>
					<!-- Upload Document -->
					<div class="col-md-6 mb-3">
						<label for="document_upload" class="form-label">Upload Vendor Agreement Document (PDF or Image)</label>
						<input type="file" class="form-control" id="document_upload" name="document_upload" accept=".pdf,image/*" required>
					</div>
                    <!-- Submit Button -->
                   <div class="col-12" id="submit-btn-wrapper" style="display: none;">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
<script src="{{ url('assets/js/jquery.min.js') }}"></script>
<script>
const sportNames = @json($sports->pluck('sports_name', 'id'));
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

function updateEquipmentOptions(sportSelect) {
    let parentDiv = sportSelect.closest('.d-flex');
    if (!parentDiv) return;

    let equipmentSelect = parentDiv.querySelector('select[name*="[equipment]"]');
    if (!equipmentSelect) return;

    const selectedSportId = sportSelect.value;
    const selectedSportName = sportNames[selectedSportId];

    const selectedSports = Array.from(document.querySelectorAll('select[name*="[game]"]'))
        .map(select => sportNames[select.value])
        .filter(Boolean);

    // Prevent both Wrestling and Judo
    if (selectedSports.includes("Wrestling") && selectedSports.includes("Judo")) {
        alert("You can only request equipment for either Wrestling or Judo, not both.");
        sportSelect.value = "";
        equipmentSelect.innerHTML = '<option value="" disabled selected>Select Equipment</option>';
        return;
    }

    // Reset equipment dropdown
    equipmentSelect.innerHTML = '<option value="" disabled selected>Select Equipment</option>';

    if (equipmentLimits[selectedSportName]) {
        Object.keys(equipmentLimits[selectedSportName]).forEach(equipment => {
            let option = document.createElement("option");
            option.value = equipment;
            option.textContent = equipment;
            equipmentSelect.appendChild(option);
        });
    }

    updateDuplicateEquipmentOptions(); // Call after populating
}

// Prevent duplicate equipment selection
function updateDuplicateEquipmentOptions() {
    const allEquipmentSelects = document.querySelectorAll('select[name*="[equipment]"]');
    const selectedEquipments = Array.from(allEquipmentSelects)
        .map(select => select.value)
        .filter(val => val !== "");

    allEquipmentSelects.forEach(select => {
        const currentValue = select.value;

        Array.from(select.options).forEach(option => {
            if (option.value === "" || option.value === currentValue) {
                option.disabled = false;
            } else {
                option.disabled = selectedEquipments.includes(option.value);
            }
        });
    });
}

document.addEventListener('change', function (e) {
    if (e.target && e.target.matches('select[name*="[equipment]"]')) {
        updateDuplicateEquipmentOptions();
    }
});

document.addEventListener('DOMContentLoaded', updateDuplicateEquipmentOptions);

// Add new game
let gameCount = 1;
function addGame() {
    const gameList = document.getElementById('games-kit-list');
    const newGame = document.createElement('div');
    newGame.classList.add('d-flex', 'mb-3');
    newGame.innerHTML = `
        <div class="col-md-3 mb-3">
            <select name="games[${gameCount}][game]" class="form-control" required onchange="updateEquipmentOptions(this)">
                <option value="">--Select Sports--</option>
                @foreach($sports as $sport)
                    <option value="{{ $sport->id }}">{{ $sport->sports_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col mb-0 px-1">
            <select name="games[${gameCount}][equipment]" class="form-control" required>
                <option value="" selected disabled>Select Equipment</option>
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <input type="number" class="form-control" name="games[${gameCount}][rate]" required>
        </div>
        <div class="col-md-3 mb-3">
            <input type="file" class="form-control" name="games[${gameCount}][photo]" accept="image/*" required />
        </div>
        <div class="col-md-1 mb-3 text-end">
            <button type="button" class="btn btn-sm btn-danger mt-4" onclick="removeGame(this)">Remove</button>
        </div>
    `;
    gameList.appendChild(newGame);
    gameCount++;
}

function removeGame(button) {
    button.closest('.d-flex').remove();
    updateDuplicateEquipmentOptions(); // Update after removal
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const documentUploadInput = document.getElementById('document_upload');
    const submitBtnWrapper = document.getElementById('submit-btn-wrapper');

    documentUploadInput.addEventListener('change', function () {
        if (documentUploadInput.files.length > 0) {
            submitBtnWrapper.style.display = 'block';
        } else {
            submitBtnWrapper.style.display = 'none';
        }
    });
});
</script>
