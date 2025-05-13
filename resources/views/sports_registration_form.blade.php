<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Club</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            border: 2px solid #007bff;
            padding: 20px;
            border-radius: 10px;
            background-color: #f8f9fa;
        }
        .profile-pic {
            display: block;
            margin: 10px auto;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 2px solid #007bff;
            object-fit: cover;
            cursor: pointer;
        }
        #profilePic {
            display: none;
        }
    </style>
     <style>
    .error-message {
        color: red;
        font-size: 14px;
        margin-top: 5px;
        display: none;
    }
</style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Sports Club</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#matches">Matches</a></li>
                    <li class="nav-item"><a class="nav-link" href="#team">Team</a></li>
                    <li class="nav-item"><a class="nav-link" href="#news">News</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <header class="bg-primary text-white text-center py-5">
        <h1>Welcome to Sports Club</h1>
        <p>Your gateway to exciting sports action!</p>
    </header>
    
    <section class="container py-5">
        <h2 class="text-center">Sports Registration Form</h2>
        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif
        <div class="form-container">
            <form action="{{ route('sports.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
            @csrf
                <div class="text-center">
                    <label class="form-label">Profile Picture</label>
                    <img id="profilePreview" class="profile-pic" src="https://via.placeholder.com/150" alt="">
                    <input type="file" id="profilePic" name="profile_picture" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label">1. Name of Sportsperson</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter your name">
                    <p id="nameError" style="color: red; display: none;">Only letters (A-Z or a-z) are allowed.</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label">2. Aadhar No.</label>
                    <input type="text" class="form-control" id="aadhar" name="aadhaar_no" maxlength="12" placeholder="Enter your 12-digit Aadhar number">
                </div>
                <div class="col-md-6">
                    <label class="form-label">3. Mobile No.</label>
                    <input type="text" class="form-control" name="phone" id="phone" maxlength="10" placeholder="Enter your phone number">
                </div>
                <div class="col-md-6">
                    <label class="form-label">4. Email ID</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                </div>
                <div class="col-md-6">
                    <label class="form-label">5. Date of Birth</label>
                    <input type="date" class="form-control" name="dob">
                </div>
                <div class="col-md-6">
                    <label class="form-label">6. Name of District to which sportsperson belongs</label>
                    <input type="text" class="form-control" name="phdistrict_sportsperson_belongsone" id="district_sportsperson_belongs" placeholder="Name of District to which sportsperson belongs">
                </div>
                <div class="col-md-6">
                    <label class="form-label">7. Domicile State</label>
                    <input type="text" class="form-control" name="domicile_state" id="domicile_state" placeholder="Domicile State">
                </div>
                <div class="col-md-6">
                    <label class="form-label">8. Plays for (Name of State/Organization)</label>
                    <select class="form-control" name="plays_for_statte_org">
                        <option value="Haryana">Haryana</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">9. Name of Sports Discipline</label>
                    <input type="text" class="form-control" name="name_sports_discipline" id="name_sports_discipline" placeholder="Name of Sports Discipline">
                </div>
                <h3>Best Sports Achievement:</h3>
                
                
                <div class="col-md-6">
                    <label class="form-label">i. Name of Tournament</label>
                    <input type="text" class="form-control" id="tournament_name" name="tournament_name" maxlength="12" placeholder="Name of Tournament">
                </div>
                <div class="col-md-6">
                    <label class="form-label">ii. Month & Year</label>
                    <input type="month" class="form-control mb-2" name="month_year">
                </div>
                <div class="col-md-6">
                    <label class="form-label">iii. Venue of Tournament</label>
                    <input type="text" class="form-control mb-2" name="venue_of_tournament" placeholder="Venue">
                </div>
                <div class="col-md-6">
                    <label class="form-label">iv. Organizing Authority</label>
                    <input type="text" class="form-control mb-2" name="organising_authority" placeholder="Venue">
                </div>
                <div class="col-md-6">
                    <label class="form-label">v. Tournament Type</label>
                    <select class="form-control" name="tournament_type">
                        <option value="Senior">Senior</option>
                        <option value="Junior">Junior</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">vi. Medal Won (if any)</label><br>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="radio" name="medal_won" value="Gold" id="medalGold">
                        <label class="form-check-label" for="medalGold">Gold</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="medal_won" value="Silver" id="medalSilver">
                        <label class="form-check-label" for="medalSilver">Silver</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="medal_won" value="Bronze" id="medalBronze">
                        <label class="form-check-label" for="medalBronze">Bronze</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">vii. Participationlevel</label>
                    <input type="text" class="form-control mb-2" name="participation_level" placeholder="Venue">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">viii. Upload Documents</label>
                    <input type="file" class="form-control" name="signature_of_sports_person">
                </div>
                <div class="col-md-6">
                    <label class="form-label">ix. Affidavit</label>
                    <input type="file" class="form-control" name="director_sports">
                </div>
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </section>
    
    <footer class="bg-dark text-white text-center py-3">&copy; 2025 Sports Club</footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("profilePreview").addEventListener("click", function() {
            document.getElementById("profilePic").click();
        });
        
        document.getElementById("profilePic").addEventListener("change", function(event) {
            let reader = new FileReader();
            reader.onload = function() {
                document.getElementById("profilePreview").src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>
   

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");

        // Function to show error messages
        function showError(input, message) {
            let errorSpan = input.nextElementSibling;
            if (!errorSpan || !errorSpan.classList.contains("error-message")) {
                errorSpan = document.createElement("span");
                errorSpan.classList.add("error-message");
                input.parentNode.appendChild(errorSpan);
            }
            errorSpan.innerText = message;
            errorSpan.style.display = "block";
        }

        // Function to remove error messages
        function clearError(input) {
            let errorSpan = input.nextElementSibling;
            if (errorSpan && errorSpan.classList.contains("error-message")) {
                errorSpan.style.display = "none";
            }
        }

        // Real-time validation
        function validateInput(input, regex, message) {
            input.addEventListener("input", function () {
                if (!regex.test(input.value.trim())) {
                    showError(input, message);
                } else {
                    clearError(input);
                }
            });
        }

        // Name Validation (Only letters & spaces)
        const nameInput = document.querySelector("input[name='name']");
        validateInput(nameInput, /^[A-Za-z\s]+$/, "Only letters and spaces are allowed.");

        // Aadhar Validation (Exactly 12 digits)
        const aadharInput = document.getElementById("aadhar");
        validateInput(aadharInput, /^\d{12}$/, "Aadhar number must be exactly 12 digits.");

        // Mobile Validation (Exactly 10 digits)
        const phoneInput = document.getElementById("phone");
        validateInput(phoneInput, /^\d{10}$/, "Mobile number must be exactly 10 digits.");

        // Email Validation
        const emailInput = document.getElementById("email");
        validateInput(emailInput, /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, "Enter a valid email address.");

        // Form Submit Validation
        form.addEventListener("submit", function (event) {
            let isValid = true;

            // Validate Name
            if (!/^[A-Za-z\s]+$/.test(nameInput.value.trim())) {
                showError(nameInput, "Only letters and spaces are allowed.");
                isValid = false;
            }

            // Validate Aadhar
            if (!/^\d{12}$/.test(aadharInput.value)) {
                showError(aadharInput, "Aadhar number must be exactly 12 digits.");
                isValid = false;
            }

            // Validate Mobile
            if (!/^\d{10}$/.test(phoneInput.value)) {
                showError(phoneInput, "Mobile number must be exactly 10 digits.");
                isValid = false;
            }

            // Validate Email
            if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(emailInput.value)) {
                showError(emailInput, "Enter a valid email address.");
                isValid = false;
            }

            // Prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>


</body>
</html>
