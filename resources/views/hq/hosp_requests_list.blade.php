@extends('hq_main')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<style>
	    label.info-label,small.info-label {font-weight: 500;font-size:14px;display: block; margin-bottom:0; line-height:normal}
	    .modal-body h5{margin:0}
	    .games-authorised-sec .row > div{font-size: 15px;padding:0}
	    .games-authorised-sec .row > div h6 {
    margin: 0;
    padding: 6px 10px;
    border-left: 1px solid rgba(0, 0, 0, 0.07);
    background: #eee;
    color: #777;
}
.games-authorised-sec .row > div:first-child h6{border:none;}
.games-authorised-sec .row > div:nth-child(-n+4){border-top:0;}
.games-authorised-sec .row > div p{padding: 5px 10px;font-size:14px;}
</style>
<h4 class="">Haryana Outstanding Sports Persons List <a href="" class="btn btn-secondary float-end"><i class="fa-solid fa-arrow-left-long"></i> Back</a></h4>
<div class=" bg-white shadow mb-5 p-2">
			<table class="table table-bordered bg-white table-hover">
				<thead>
					<tr class="bg-primary text-white">
					<th>Sr. No.</th>
					<th>Application Id</th>
					<th>Name</th>
					<th>Block</th>
					<th>District</th>
					<th>Ward/Village</th>
					<th>Event Type</th>
					<th>Tournament</th>
					<th>Physical Disablity</th>
					<th>Level of Tournament</th>
					<th>Game</th>
					<th>venue</th>
					<th>Medal</th>
					<th>Achievement Date</th>
					<th>Participation level</th>
					<th>Application status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($users as $index => $request)	
				<tr>
					<td>{{ $index + 1 }}.</td>
					<td>{{ $request['applicant_id'] }}</td>
					<td>{{ $request['user_details']['full_name_en'] }}</td>
					<td>{{ $request['user_details']['block_town'] }}</td>
					<td>{{ $request['user_details']['district'] }}</td>
					<td>{{ $request['user_details']['ward_village'] }}</td>
					<td>In-Progress</td>
					<td>
					
						<form action="" method="POST" style="display:inline;">
							@csrf
							<button type="submit"  class="btn btn-success w-100 mb-2">
								Approve
							</button>
						</form>

						<form action="" method="POST" style="display:inline;">
							@csrf
							<button type="submit" class="btn btn-danger w-100">
								Reject
							</button>
						</form>
				</td>
				</tr>
				
				@endforeach
			</tbody>
		</table>
			
	</div>	
	
</div>			
				

            
     <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header bg-success">
					<h5 class="modal-title" id="exampleModalLabel">Sports Equipments Center</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 col-sm-6 col-md-3">
							<label>1. Name of the Owner</label>
							<h6>Navjot Kaur</h6>
						</div>
						<div class="col-sm-12 col-sm-6 col-md-3">
							<label>2. PAN No. </label>
							<h6>123Jdf123</h6>
						</div>
						<div class="col-sm-12 col-sm-6 col-md-6">
							<label>3. Firm Address</label>
							<h6>Sco. 109-110, Sector 17B, Chandigarh</h6>
						</div>
						<div class="col-sm-12 col-sm-6 col-md-3">
							<label>4. District</label>
							<h6>Chandigarh</h6>
						</div>
						<div class="col-sm-12 col-sm-6 col-md-3">
							<label>5. Pincode</label>
							<h6>160017</h6>
						</div>
						<div class="col-12 mt-3 games-authorised-sec">
							<p>Games Kit Authorised</p>
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-3">
									<div class="card">
										<div class="card-body text-center">
											<h4>Volleyball</h4>
											<p>₹ 300 (per unit)</p>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<div class="card">
										<div class="card-body text-center">
											<h4>Basketball</h4>
											<p>₹ 500 (per unit)</p>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<div class="card">
										<div class="card-body text-center">
											<h4>Handball</h4>
											<p>₹ 300 (per unit)</p>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<div class="card">
										<div class="card-body text-center">
											<h4>Cricket</h4>
											<p>₹ 300 (per unit)</p>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<div class="card">
										<div class="card-body text-center">
											<h4>Judo</h4>
											<p>₹ 300 (per unit)</p>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<div class="card">
										<div class="card-body text-center">
											<h4>Wrestling</h4>
											<p>₹ 300 (per unit)</p>
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
    </div>
	
    @endsection
    <script src="{{ url('assets/js/jquery.min.js') }}"></script>
		<script src="{{ url('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
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
		
		$(document).ready(function(){
			$('.navbar-toggler').click(function(){
				$('aside').toggleClass('main');
			});
		
		});
			
	</script>