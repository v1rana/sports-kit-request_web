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

	<script src="{{ url('assets/job_app/dash/js/jquery.min.js') }}"></script>
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