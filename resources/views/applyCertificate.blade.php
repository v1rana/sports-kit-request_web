	@extends('layouts.dashboard')

	@section('title', 'Sports !! Dashborad')

	@section('content')
	@if (session('success'))
		<div class="alert alert-success">
			{{ session('success') }}
		</div>
	@endif

	<section>
		<div class="container mt-5 ">
			<h4 class="">Sports Gradation Application <a href="{{ route('dashboard') }}" class="btn btn-secondary float-end"><i class="fa-solid fa-arrow-left-long"></i> Back</a></h4>
			<div class=" bg-white shadow mb-5">
				<div class="row justify-content-between py-1 border-bottom align-items-center">
					<div class="col-12">
						<form action="{{ route('sports.store') }}" method="POST" enctype="multipart/form-data" class="px-5 py-2">
						@csrf	<div class="row justify-content-end">
								<div class="col-xs-12 col-sm-6 col-md-4 mb-3 text-end">
									<div class="form-group ">
										<label class="form-label" for="img-upload">Upload Self-attested Photo</label>
										<img id="img-upload" src="{{ url('storage/' . ($otpData->profile_picture ?? 'default.jpg')) }}" class="mb-1" />
										<div class="input-group me-4">
											<span class="input-group-btn">
												<span class="btn btn-white btn-file border">
													<span class="">Browse… </span><input type="file" name="profile_picture" class="" id="imgInp">
												</span>
											</span>
											<input type="text" name="img" class="form-control" readonly>
										</div>
									</div>
								</div></div><div class="row">
								<div class="col-xs-12 col-sm-6 col-md-4 mb-3">
									<label for="name" class="form-label">1. Name of Sportsperson<span class="text-danger">*</spna></label>
									<input type="text" class="form-control" required id="name" name="name" value="{{ $otpData->sports_person_name ?? '' }}" readonly oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'');">
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 mb-3">
									<label for="adhaarNo" class="form-label">2. Aadhaar No. <span class="text-danger">*</spna></label>
									<input type="text" class="form-control" id="adhaarNo" required name="aadhaar_no" value="{{ $otpData->aadhaar_no ?? '' }}" maxlength="12" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 mb-3">
									<label for="mobileNo" class="form-label">3. Mobile No. <span class="text-danger">*</spna></label>
									<input type="text" class="form-control" id="mobileNo" required id="phone" value="{{ $otpData->mobile_no ?? '' }}" readonly maxlength="10" oninput="this.value=this.value.replace(/[^0-9]/g,'');if(!/^6\d{0,}$/.test(this.value)) this.value='';if(this.value.length > 0 && this.value.length < 6) this.setCustomValidity('Mobile number must be at least 6 digits');else this.setCustomValidity('');">
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 mb-3">
									<label for="district" class="form-label">4. Name of District sportsperson belongs to <span class="text-danger">*</spna></label>
									<select class="form-control required" id="district_sportsperson_belongs" name="district_sportsperson_belongs" required>
										<option value="">--Select--</option>
										@foreach($GetDistricts as $districts)									    
										<option value="{{ $districts->name }}">{{ $districts->name }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 mb-3">
									<label for="domicileNo" class="form-label">5. Domicile State <span class="text-danger">*</spna></label>
									<select class="form-control required" id="domicile_state" name="domicile_state" required>
										<option value="">--Select--</option>
										@foreach($state as $val)									    
											<option value="{{ $val->name }}" {{ $val->name == 'Haryana' ? 'selected' : '' }}>
												{{ $val->name }}
											</option>
										@endforeach
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 mb-3">
									<label for="playsFor" class="form-label">6. Plays for (Name of State/Organization) <span class="text-danger">*</spna></label>
									
									<select class="form-control required" id="plays_for_statte_org" name="plays_for_statte_org" required>
										<option value="">--Select--</option>
										@foreach($state as $val)									    
											<option value="{{ $val->name }}">
												{{ $val->name }}
											</option>
										@endforeach
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 mb-3">
									<label for="sportsDiscipline" class="form-label">7. Name of Sports Discipline <span class="text-danger">*</spna></label>
									<select class="form-control required" id="name_sports_discipline" name="name_sports_discipline" required>
										<option value="">--Select--</option>
										<option value="">--Select--</option>
										@foreach($GetSportName as $val)									    
											<option value="{{ $val->name }}">{{ $val->name }}</option>
										@endforeach
									</select>
								</div>
								
								<h5 class="mt-4">8. Best Sports Achievement</h5>
								
								<div class="col-xs-12 col-sm-6 col-md-12 mb-3">
									<label for="tournamentName" class="form-label">i. Name of Tournament <span class="text-danger">*</spna></label>
									<select class="form-control required" id="tournamentName" name="tournament_name" required>
										<option value="">--Select--</option>
										@foreach($tournament as $tour)									    
										<option value="{{ $tour->id }}">{{ $tour->tournament }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 mb-3">							
									<label for="monthYear" class="form-label">ii. Month & Year <span class="text-danger">*</spna></label>
									<input type="month" class="form-control" required id="monthYear" name="month_year" value="{{ $otpData->month_year ?? '' }}" placeholder="">						
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 mb-3">
									<label for="venueTournament" class="form-label">iii. Venue of Tournament <span class="text-danger">*</spna></label>
									<input type="text" class="form-control" required id="venueTournament" name="venue_of_tournament" value="{{ $otpData->venue_of_tournament ?? '' }}" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'');">							
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 mb-3">
									<label for="organizingAuthority" class="form-label">iv. Organizing Authority <span class="text-danger">*</spna></label>
									<select class="form-control required" id="organising_authority" name="organising_authority" required>
																			
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 mb-3">
									<p>v. Tournament Type <span class="text-danger">*</spna></p>
									<div class="form-check form-check-inline">
										<input class="form-check-input" required type="radio" name="tournament_type" id="tournamentSenior" value="Senior" 
											{{ isset($otpData->tournament_type) && $otpData->tournament_type == 'Senior' ? 'checked' : '' }}>
										<label class="form-check-label" for="tournamentSenior">Senior</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" required type="radio" name="tournament_type" id="tournamentJunior" value="Junior" 
											{{ isset($otpData->tournament_type) && $otpData->tournament_type == 'Junior' ? 'checked' : '' }}>
										<label class="form-check-label" for="tournamentJunior">Junior</label>
									</div>
								</div>

								<div class="col-xs-12 col-sm-6 col-md-4 mb-3">
									<p>vi. Medal won (if any) <span class="text-danger">*</spna></p>
									<div class="form-check form-check-inline">
										<input class="form-check-input" required type="radio" name="medal_won" id="medalGold" value="Gold" 
											{{ isset($otpData->medal_won) && $otpData->medal_won == 'Gold' ? 'checked' : '' }}>
										<label class="form-check-label" for="medalGold">Gold</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" required type="radio" name="medal_won" id="medalSilver" value="Silver" 
											{{ isset($otpData->medal_won) && $otpData->medal_won == 'Silver' ? 'checked' : '' }}>
										<label class="form-check-label" for="medalSilver">Silver</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" required type="radio" name="medal_won" id="medalBronze" value="Bronze" 
											{{ isset($otpData->medal_won) && $otpData->medal_won == 'Bronze' ? 'checked' : '' }}>
										<label class="form-check-label" for="medalBronze">Bronze</label>
									</div>
								</div>

								<div class="col-xs-12 col-sm-6 col-md-4 mb-3">
									<p>vii. Participation Level (in case of team game only) <span class="text-danger">*</spna></p>
									<div class="form-check form-check-inline">
										<input class="form-check-input" required type="radio" name="participation_level" id="inlineRadio1" value="25% or more" 
											{{ isset($otpData->participation_level) && $otpData->participation_level == '25% or more' ? 'checked' : '' }}>
										<label class="form-check-label" for="inlineRadio1">25% or more</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" required type="radio" name="participation_level" id="inlineRadio2" value="Less than 25%" 
											{{ isset($otpData->participation_level) && $otpData->participation_level == 'Less than 25%' ? 'checked' : '' }}>
										<label class="form-check-label" for="inlineRadio2">Less than 25%</label>
									</div>
								</div>
							</div>
							<div class="col-12 mt-4 attachment-upload-area">
								<h5>Attachments</h5>
								<!-- Aadhaar Card -->
								<div class="row">
									<div class="col mb-3 border-end">
										<label for="aadhaar_card" class="form-label">Aadhaar Card (PDF/JPG) <span class="text-danger">*</span></label>
										<input type="file" class="form-control" id="aadhaar_card" name="aadhaar_card" accept=".pdf,.jpg,.jpeg,.png" required onchange="previewFile(event, 'aadhaarPreview')">
										<div id="aadhaarPreview" class="preview-container"></div>
									</div>

									<!-- Domicile Certificate -->
									<div class="col mb-3 border-end">
										<label for="domicile_certificate" class="form-label">Domicile Upload (PDF/JPG) <span class="text-danger">*</span></label>
										<input type="file" class="form-control" id="domicile_certificate" name="domicile_certificate" accept=".pdf,.jpg,.jpeg,.png" required onchange="previewFile(event, 'domicilePreview')">
										<div id="domicilePreview" class="preview-container"></div>
									</div>

									<!-- Sports Achievement Certificate -->
									<div class="col mb-3 border-end">
										<label for="sports_certificate" class="form-label">Achievement Certificate Upload (PDF/JPG) <span class="text-danger">*</span></label>
										<input type="file" class="form-control" id="sports_certificate" name="sports_certificate" accept=".pdf,.jpg,.jpeg,.png" required onchange="previewFile(event, 'sportsPreview')">
										<div id="sportsPreview" class="preview-container"></div>
									</div>

									<!-- Self-Attested Photograph -->
									<div class="col mb-3">
										<label for="self_attested_photo" class="form-label">Certificate for as Proof for Playing more than 25% of matches. (PDF/JPG)<span class="text-danger">*</span></label>
										<input type="file" class="form-control" id="more_than25_photo" name="more_than25_photo" accept=".pdf,.jpg,.jpeg,.png" required onchange="previewFile(event, 'photoPreview')">
										<div id="photoPreview" class="preview-container"></div>
									</div>
								</div>
							</div>
							<div class="col-12 mt-4 shadow" style="background-color: bisque;padding: 21px 0px;">    
								<h4 class="form-check-label ps-4">Declaration</h4>
							
								<div class="form-check">
							✅ <label class="form-check-label" for="declare1">
										I certify that I am currently a domicile/resident of Haryana.
									</label>
								</div>
								<div class="form-check">
								✅
									<label class="form-check-label" for="declare2">
									I certify that I have never played for any State or Union Territory other than Haryana.
									</label>
								</div>
								<div class="form-check">
								✅
									<label class="form-check-label" for="declare3">
									I certify that I have played for Haryana at the National/International Level.
									</label>
								</div>
								<div class="form-check">
								✅
									<label class="form-check-label" for="declare4">
									I certify that I have not been penalized for any unfair practice like age fraud, doping, etc., in the tournament for which cash award is being applied for.
									</label>
								</div>
								<div class="form-check">
								✅
									<label class="form-check-label" for="declare5">
									I certify that I have enclosed the self-attested copies of the documents as per requirements.
									</label>
								</div>
								<div class="form-check">
								
									<label class="form-check-label" for="declare6">
									✅ I also understand that if any information provided by me for the grant of Gradation Certificate is found to be false or incorrect, then I shall be liable for any penal action.
									</label>
								</div>
							</div>
							<hr />
							<div class="row mb-3">					
								<div class="col-12 text-end">
									<a href="" class="btn btn-secondary">Reset</a>
									<button type="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</form>
					</div>	
				</div>	
			</div>			
		</div>
	</section>

	@endsection