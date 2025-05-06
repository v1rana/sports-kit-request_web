<footer>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-xs-12 col-sm-8">
				<p>All rights reserved. Powered by <strong>Citizen Resources Information Department, Haryana</strong></p>
			</div>
			<div class="col-xs-12 col-sm-4 text-end">
				<div class="visitor-counter">
				
					<strong>Visitor Count</strong> <span>130361</span>
				</div>
			</div>
		</div>
	</div>
</footer>
	

<!-- Bootstrap JS (Include this if it's missing) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<script src="{{ url('assets/job_app/dash/js/jquery.min.js') }}"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const eventTypeSelect = document.getElementById('type_of_event');
        const participationSection = document.getElementById('participation_level_section');

        function toggleParticipationLevel() {
            if (eventTypeSelect.value === 'Team') {
                participationSection.style.display = 'block';
            } else {
                participationSection.style.display = 'none';

                // Optionally clear selection when hidden
                document.querySelectorAll('input[name="participation_level"]').forEach(el => el.checked = false);
            }
        }

        // Initial toggle on page load
        toggleParticipationLevel();

        // Toggle on change
        eventTypeSelect.addEventListener('change', toggleParticipationLevel);
    });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const radioLess = document.getElementById('inlineRadio2');

    if (radioLess) {
      radioLess.addEventListener('change', function () {
        if (this.checked) {
          const modal = new bootstrap.Modal(document.getElementById('ineligibleModal'));
          modal.show();
        }
      });
    }
  });
</script>

<script>
  document.getElementById('myDate').max = new Date().toISOString().split("T")[0];
</script>
<script>
    document.getElementById('submitTrigger').addEventListener('click', function () {
        const modal = new bootstrap.Modal(document.getElementById('submitConfirmModal'));
        modal.show();
    });

    document.getElementById('confirmSubmit').addEventListener('click', function () {
        document.getElementById('submitForm').submit();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('uploadForm');
        const confirmBtn = document.getElementById('confirmUpload');

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // stop form submission
            const modal = new bootstrap.Modal(document.getElementById('uploadConfirmModal'));
            modal.show();

            // On confirmation, submit form
            confirmBtn.onclick = function () {
                modal.hide();
                form.submit();
            };
        });
    });
</script>
<script>
function showFileName() {
    var fileInput = document.getElementById('fileInput');
    var fileName = document.getElementById('fileName');
    
    if (fileInput.files.length > 0) {
        fileName.textContent = 'Selected File: ' + fileInput.files[0].name;
    } else {
        fileName.textContent = '';
    }
}

function uploadFile() {
        var fileInput = document.getElementById('fileInput');
        var fileName = document.getElementById('fileName'); // Element to display file name

        alert(fileInput);
        
        if (fileInput.files.length === 0) {
            alert('No file selected!');
            return;
        }

        var formData = new FormData();
        formData.append('file', fileInput.files[0]);
        console.log(formData.file);
        fetch("{{ route('file.upload') }}", {
            method: 'POST',
            body: formData,
            headers: { 
                    "Content-Type": "application/json", 
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                },
        })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Log response for debugging
            if (data.success) {
                alert('File uploaded successfully!');
                fileInput.value = '';  // Clear file input
                fileName.textContent = '';  // Clear file name (if you're showing it in an element)
            } else {
                alert('Error uploading file: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error uploading file1: ' + error);
        });
    }
</script>
<script>
  function downloadPDF() {
    const element = document.getElementById("certificateCard");

    // Temporarily make the hidden card visible just for PDF generation
    element.style.display = 'block';

    const opt = {
      margin: 0.1,
      filename: 'sports-certificate.pdf',
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(element).save().then(() => {
      element.style.display = 'none'; // Hide again after download
    });
  }
</script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#img-upload').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function(){
        readURL(this);
    }); 
</script>
	<script>
$(document).ready(function() {
    $('#tournamentName').on('change', function() {
        var tournamentId = $(this).val(); // Get selected tournament ID
        if (tournamentId) {
            $.ajax({
				url: "{{ route('get.organising.authority') }}", // Route for AJAX
				type: "GET",
				data: { tournament_id: tournamentId },
				success: function(response) {
					console.log("Response:", response); // Debugging log
					$('#organising_authority').empty().append('<option value="">--Select--</option>'); 
					
					$.each(response, function(key, value) {
						$('#organising_authority').append('<option value="' + value + '">' + value + '</option>');
					});
				},
				error: function(xhr, status, error) {
					console.log("AJAX Error:", xhr.responseText); // Log error message
				}
			});
        } else {
            $('#organising_authority').html('<option value="">--Select--</option>'); // Reset if no selection
        }
    });
});
</script>
<script>
    function previewFile(event, previewId) {
        var file = event.target.files[0];
        var previewContainer = document.getElementById(previewId);
        previewContainer.innerHTML = ''; // Clear previous preview

        if (file) {
            var reader = new FileReader();

            reader.onload = function(e) {
                if (file.type.includes("image")) {
                    // Display Image Preview
                    var img = document.createElement("img");
                    img.src = e.target.result;
                    img.style.width = "150px";
                    img.style.height = "150px";
                    img.style.border = "1px solid #ddd";
                    img.style.borderRadius = "5px";
                    img.style.marginTop = "5px";
                    previewContainer.appendChild(img);
                } else if (file.type === "application/pdf") {
                    // Display PDF File Name
                    var link = document.createElement("a");
                    link.href = e.target.result;
                    link.innerText = file.name;
                    link.target = "_blank";
                    link.style.display = "block";
                    link.style.marginTop = "5px";
                    previewContainer.appendChild(link);
                }
            };

            reader.readAsDataURL(file);
        }
    }
</script>
<script>
    function viewDetails(pro_pic,name, adhar_no, phone, belongTo,domiState,organisation,sport_displ,nameOfTounmnt,
    month_year,vanueOfTournam,ornAthority,tounType,modalMedal,patiLevel,modalAadhaar,modalDomicile,modalSportsCert,more_than25_photo) {
        document.getElementById("modalProfilePic").src = pro_pic;
        document.getElementById('modalName').textContent = name;
        document.getElementById('adhar_no').textContent = adhar_no;
        document.getElementById('modalPhone').textContent = phone;
        document.getElementById('belongTo').textContent = belongTo;
        document.getElementById('domiState').textContent = domiState;
        document.getElementById('organisation').textContent = organisation;
        document.getElementById('sport_displ').textContent = sport_displ;
        document.getElementById('nameOfTounmnt').textContent = nameOfTounmnt;
        document.getElementById('month_year').textContent = month_year;
        document.getElementById('vanueOfTournam').textContent = vanueOfTournam;
        document.getElementById('ornAthority').textContent = ornAthority;
        document.getElementById('tounType').textContent = tounType;
        document.getElementById('modalMedal').textContent = modalMedal;
        document.getElementById('patiLevel').textContent = patiLevel;
        document.getElementById("modalAadhaar").href = modalAadhaar;
        document.getElementById("modalDomicile").href = modalDomicile;
        document.getElementById("modalSportsCert").href = modalSportsCert;
        document.getElementById("more_than25_photo").href = more_than25_photo;

        var modal = new bootstrap.Modal(document.getElementById('detailsModal'));
        modal.show();
    }
</script>