<footer>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-7">
                <p>All rights reserved. Powered by <strong>Citizen Resources Information Department, Haryana</strong></p>
            </div>
            <div class="col-xs-12 col-sm-3 text-end">
                <div class="visitor-counter">                
                    <strong>Visitor Count</strong> <span>130361</span>
                </div>
            </div>
        </div>
    </div>
</footer>
<meta name="csrf-token" content="{{ csrf_token() }}">	
<script src="{{ url('assets/job_app/js/jquery.min.js') }}"></script>
<script>
 $(document).ready(function(){
    var currentStep = 0;
    var totalSteps = $(".step").length;
    $(".step").hide();
    $(".step").eq(currentStep).show();

    // Next button click handler
    $("#nextButton").click(function(){
        if (!validateStep(currentStep)) {
            return; // Prevent moving to the next step if validation fails
        }

        // Hide the current step
        $(".step").eq(currentStep).hide();
        currentStep++;

        // Show the next step
        $(".step").eq(currentStep).show();

        // Show the Previous button when we are not on the first step
        $("#previousButton").toggle(currentStep > 0);       
        if(currentStep >= 3)
        {
            let mobile = document.getElementById('mobile').value;

            fetch("{{ route('otp.send') }}", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json", 
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                },
                body: JSON.stringify({ mobile: mobile })
            })
            .then(response => response.json())  // Convert response to JSON
            .then(data => {
                console.log(data);  // Log response to console
                //alert(data.message);  // Show response message
            })
            .catch(error => {
                console.error("Error:", error);  // Log errors if any
                alert("An error occurred while sending OTP.");
            });
        }

        // If we are on the last step, hide the "Next" button and show the "Submit" button
        $("#nextButton").toggle(currentStep < totalSteps - 1);
        //$("#submitButton").toggle(currentStep == totalSteps - 1);

        
    });

    $("#sendotp").click(function() {

        $('#alert').hide();
        if (!validateStep(currentStep)) {
            return; // Prevent moving to the next step if validation fails
        }

        let mobile = document.getElementById('mobile').value;

        // if (!mobile || mobile.length !== 10) {
        //     alert("Please enter a valid 10-digit mobile number.");
        //     return;
        // }

        fetch("{{ route('login.otp.send') }}", {
            method: "POST",
            headers: { 
                "Content-Type": "application/json", 
                "X-CSRF-TOKEN": "{{ csrf_token() }}" 
            },
            body: JSON.stringify({ mobile: mobile })
        })
        .then(response => response.json())  // Convert response to JSON
        .then(data => {
            if (data.success) {
                // OTP sent successfully, move to the next step
                $(".step").eq(currentStep).hide();
                currentStep++;
                $(".step").eq(currentStep).show();

                $("#previousButton").toggle(currentStep > 0);   
                $("#sendotp").toggle(currentStep < totalSteps - 1);
                $("#submitButton").toggle(currentStep == totalSteps - 1);
            } else {
                // Show error message and stay on the same page
                alert(data.message || "Mobile number is not registered.");
            }
        })
        .catch(error => {
            console.error("Error:", error);  // Log errors if any
            alert("An error occurred while sending OTP.");
        });
});



    // Previous button click handler
    $("#previousButton").click(function(){
        // Hide the current step
        $(".step").eq(currentStep).hide();
        currentStep--;

        // Show the previous step
        $(".step").eq(currentStep).show();

        // Show the Next button again if we are not on the last step
        $("#nextButton").toggle(currentStep < totalSteps - 1);
        $("#sendotp").toggle(currentStep < totalSteps - 1);
        $("#submitButton").hide();

        // Hide the Previous button when we are on the first step
        $("#previousButton").toggle(currentStep > 0);
    });

    // Form Validation Function
    function validateStep(step) {
        let isValid = true;
        let inputs = $(".step").eq(step).find(".required");

        inputs.each(function() {
            if ($(this).val().trim() === "") {
                $(this).addClass("is-invalid");
                isValid = false;
            } else {
                $(this).removeClass("is-invalid");
            }
        });

        return isValid;
    }
});

document.getElementById('verifyOtpBtn').addEventListener('click', function() {
    let otp = document.getElementById('otp').value;
    let mobile = document.getElementById('mobile').value;

    if (otp.trim() === "") {
        document.getElementById('otperror').style.display = 'block';
        document.getElementById('otpInvalid').style.display = 'none';
        document.getElementById('resenderror').style.display = 'none';
        
        return false;
    } else {
        document.getElementById('otperror').style.display = 'none';
        document.getElementById('otpInvalid').style.display = 'none';
    }

    fetch("{{ route('otp.verify') }}", {
        method: "POST",
        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: JSON.stringify({ mobile: mobile, otp: otp })
    })

    .then(response => response.json())  
    .then(data => {
        
        if (data.message === "OTP verified successfully") {
            document.getElementById('otpInvalid').style.display = 'none';
            document.getElementById('verified').style.display = 'block';
            document.getElementById('verifyOtpBtn').style.display = 'none';
            document.getElementById('submitButton').style.display = 'inline-block';
            document.getElementById('resenderror').style.display = 'none';
        } else {
            document.getElementById('otpInvalid').style.display = 'block';
            document.getElementById('verified').style.display = 'none';
            document.getElementById('submitButton').style.display = 'none';
            document.getElementById('resenderror').style.display = 'none';
        }
    })
    .catch(error => {
        console.error("Error:", error);
        document.getElementById('otpInvalid').style.display = 'block';
        document.getElementById('verified').style.display = 'none';
        alert(error.message || "An error occurred while verifying OTP.");
    });
});

document.getElementById('resendOtp').addEventListener('click', function() {
    let mobile = document.getElementById('mobile').value;

    fetch("{{ route('otp.resend') }}", {
        method: "POST",
        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: JSON.stringify({ mobile: mobile})
    })

    .then(response => response.json())  // Convert response to JSON
    .then(data => {
        document.getElementById('resenderror').style.display = 'block';
        document.getElementById('otpInvalid').style.display = 'none';
        document.getElementById('verifyOtpBtn').style.display = 'block';
        document.getElementById('verified').style.display = 'none';
        document.getElementById('otperror').style.display = 'none';
    })
    .catch(error => {
        console.error("Error:", error);  // Log errors if any
        alert("An error occurred while sending OTP.");
    });
});
</script>
<script>
    document.querySelectorAll('.resendOtp').forEach(function(element) {
        element.addEventListener('click', function(event) {
            event.preventDefault();
            let mobile = document.getElementById('mobile').value;

            fetch("{{ route('otp.resend') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ mobile: mobile})
            })

            .then(response => response.json())  // Convert response to JSON
            .then(data => {
                document.getElementById('resenderror').style.display = 'block';
                document.getElementById('otpInvalid').style.display = 'none';
                document.getElementById('verifyOtpBtn').style.display = 'block';
                document.getElementById('verified').style.display = 'none';
                document.getElementById('otperror').style.display = 'none';
            })
        });
    });

    function validateGmail(input) {
        let email = input.value;
        let regex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
        let errorMessage = document.getElementById("error-message");

        if (!regex.test(email)) {
            errorMessage.textContent = "Only Gmail addresses are allowed!";
        } else {
            errorMessage.textContent = "";
        }
    }

    // window.setTimeout(function() {
    //     $(".alert").fadeTo(500, 0).slideUp(500, function(){
    //         $(this).remove(); 
    //     });
    // }, 4000);
</script>

    
    
    


