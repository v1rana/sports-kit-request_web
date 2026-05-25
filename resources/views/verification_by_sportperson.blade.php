@extends('layouts.dashboard')

@section('title', 'Application for Sports Gradation Certificate')

@section('content')
<style>
    .section-title {
      font-weight: bold;
      color: #dc3545;
    }
    .info-label {
      font-weight: 500;
    }
    .doc-preview img {
      max-width: 100%;
      height: auto;
      border-radius: 6px;
    }
    .card-header {
      background-color: #f8f9fa;
      font-weight: bold;
    }
    .details-area > div {border-bottom: 1px solid rgba(0,0,0,0.04)}
    /**.uploaded-doca-area .card-body p {
    height: auto;width:100%;
    float: left;
    background: rgba(0,0,0,0.04);*/
}
.card-body{padding: 0 0.7rem}
.uploaded-doca-area .card-body a {
    border-radius: 0 0 4px 4px;
}
h3 i {
    text-shadow: 0 0 3px rgba(0,0,0,.3);
    color: #000;
}
h3 {
    background: rgba(0,0,0,0.04);
    padding: 10px;
    color: #36454F;
    font-size: 20px;
    text-transform: uppercase;
}h5 {
    font-size: 18px;
}
  </style>
</head>
<body>

<div class="container my-4">

  <h4>Application ID - <strong>{{ $otpData->appl_id }}</strong></h4>
  <!-- Personal Info Card -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="row justify-content-between">
        <!-- Personal Details Starts -->

        <h3><i class="fa-solid fa-user-large"></i> Personal Details</h3>
        <div class="col-md-10">
          <div class="row details-area">
            <div class="col-md-3 mb-3">          
              <small class="info-label text-muted ">1. Name of the Sportperson</small>
              <h5>{{ $otpData->sports_person_name }}</h5>
            </div>
            <div class="col-md-3 mb-3"> 
              <small class="info-label text-muted ">2. Aadhar No.</small>
              <h5>{{ $otpData->aadhaar_no }}</h5>
            </div>
            <div class="col-md-3 mb-3">   
              <small class="info-label text-muted ">3. Mobile Number</small>
              <h5>{{ $otpData->mobile_no }}</h5>
            </div>
            <div class="col-md-3 mb-3"> 
              <small class="info-label text-muted">4. Name of District sportsperson belongs to</small>
              <h5>{{ $otpData->district_sportsperson_belongs }}</h5>
            </div>
            <div class="col-md-3 mb-3"> 
              <small class="info-label text-muted ">5. Domicile State</small>
              <h5>{{ $otpData->domicile_state }}</h5>
            </div>
            <div class="col-md-3 mb-3"> 
              <small class="info-label text-muted ">6. Plays for (Name of State/Organization)</small>
              <h5>{{ $otpData->plays_for_statte_org }}</h5>
            </div>
            <div class="col-md-3 mb-3"> 
              <small class="info-label text-muted ">7. Name of Sports Discipline</small>
              <h5>{{ $otpData->name_sports_discipline }}</h5>
            </div>
            <div class="col-md-3 mb-3"> 
                <small class="info-label text-muted">Type of Event</small>
                <h5>{{ $otpData->type_of_event }}</h5>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <img src="{{ url('storage/' . ($otpData->profile_picture ?? 'default.jpg')) }}" width="150px" height="150px" style="border:5px solid #eee" />
        </div>
        <!-- Personal Details Ends -->
      </div>

      <!-- Achievement Details Starts -->
      <div class="row mt-4 details-area">         
        <h3><i class="fa-solid fa-trophy"></i> Achievement Details</h3>       
        <div class="col-md-6 mb-3">
          <small class="info-label text-muted">Tournament</small>
          <h5>{{ $otpData->tournament }}</h5>
        </div>
        <div class="col-md-3 mb-3">
          <small class="info-label text-muted">Name of the Tournament</small>
          <h5>{{ $otpData->name_of_tournamentN }}</h5>
        </div>        
        <div class="col-md-3 mb-3"> 
          <small class="info-label text-muted">Date</small>
          <h5>{{ \Carbon\Carbon::parse($otpData->month_year)->format('d-m-Y') }}</h5>
        </div>
        <div class="col-md-3 mb-3"> 
          <small class="info-label text-muted">Venue of Tournament</small>
          <h5>{{ $otpData->venue_of_tournament }}</h5>
        </div>
        <div class="col-md-6 mb-3">
          <small class="info-label text-muted">Organizing Authority</small>             
          <h5 >{{ $otpData->organising_authority }}</h5>
        </div>   
        <div class="col-md-3 mb-3"> 
            <small class="info-label text-muted">Medal Won(If any)</small>
            <h5>{{ $otpData->medal_won }}</h5>
        </div>
        <div class="col-md-3 mb-3"> 
          <small class="info-label text-muted">Participation Level</small>
          <h5>{{ $otpData->participation_level }}</h5>
        </div>
        <div class="col-md-12 mb-3"> 
          <small class="info-label text-muted">Tournament Type</small>
          <h5>{{ $otpData->tournament_type }}</h5>
        </div>
      </div>
      <!-- Achievement Details Ends -->
      
       <!-- Uploaded Documents Starts -->
      <div class="row mt-4 uploaded-doca-area">         
        <h3><i class="fa-solid fa-folder-open"></i> Uploaded Documents</h3>       
        <div class="col-2 mb-3">
          <img src="{{ url('storage/' . ($otpData->aadhaar_card ?? 'default.jpg')) }}" width="200px" height="200px" style="border:5px solid #eee" />
          <h5 class="mt-2">1. Aadhaar Card</h5>           
        </div>
        <div class="col-2 mb-3">
          <img src="{{ url('storage/' . ($otpData->domicile_certificate ?? 'default.jpg')) }}" width="200px" height="200px" style="border:5px solid #eee" />
          <h5 class="mt-2">2. Domicile Proof</h5>           
        </div>
        <div class="col-2 mb-3">
          <img src="{{ url('storage/' . ($otpData->sports_certificate ?? 'default.jpg')) }}" width="200px" height="200px" style="border:5px solid #eee" />
          <h5 class="mt-2">3. Achievement Certificate</h5>             
        </div>
        <div class="col-2 mb-3">
          <img src="{{ url('storage/' . ($otpData->noc_upload ?? 'default.jpg')) }}" width="200px" height="200px" style="border:5px solid #eee" />
          <h5 class="mt-2">4. NOC Upload (for Certifying Played from Other State/UT/Organisation)</h5>             
        </div>
        @if($otpData->more_than25_photo != "")
        <div class="col-md-2 mb-3">
          <img src="{{ url('storage/' . ($otpData->more_than25_photo ?? 'default.jpg')) }}" width="200px" height="200px" style="border:5px solid #eee" />
          <h5 class="mt-2">5. Certificate for as proof for playing more than 25% of matches</h5>            
        </div>                          
        @endif 
      </div>
      <hr>
      <div class="row mt-4 uploaded-doca-area"> 
    @php
        $docs = [
            'date_ofbirth_certificate' => 'Date of Birth Certificate',
            'verif_fron_conc_auth'    => 'Verification from Concerned Authority',
            'affidavit_uplod'         => 'Affidavit Upload',
            'coach_certif'            => 'Coach Certificate',
        ];
    @endphp

    @foreach($docs as $field => $label)
        @php
            $file = $otpData->$field ?? 'default.jpg';
            $fileUrl = asset('storage/' . $file);
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        @endphp

        <div class="col-3 mb-3">
            <small class="info-label text-muted">{{ $label }}</small><br />
            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-success">
                <i class="fa-solid fa-file-arrow-down"></i> 
                {{ $ext === 'pdf' ? 'PDF' : 'View' }}
            </a>                  
        </div>
    @endforeach
</div>

      <hr />
      <!-- Action Buttons Starts -->
      <div class="row align-items-end">
        <div class="col-md-12">
          <div class="d-flex justify-content-between align-items-center">
            <!-- Download Performa Button -->
            <div>
                <small class="info-label text-muted">Download Unsigned Application</small><br />
                <a href="#" class="btn btn-success" onclick="downloadPDF()">
                    <i class="fa-solid fa-file-arrow-down"></i> PDF
                </a>
            </div>

            <!-- Upload and Remove Forms (in one flex container) -->
            <div class="">
                <small class="info-label text-muted">Upload Signed Application</small><br />

                <!-- Upload Form -->
                <div class="d-flex gap-2">
                  <form id="uploadForm" action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                      @csrf
                      <input type="hidden" name="applId" value="{{ $otpData->id }}">
                      <input type="file" class="form-control form-control-sm" name="VerificationFile" required>
                      <button class="btn btn-warning btn-sm" type="submit">Upload</button>

                  </form>
                  <!-- Upload Confirmation Modal -->
                  <div class="modal fade" id="uploadConfirmModal" tabindex="-1" aria-labelledby="uploadConfirmLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                          <div class="modal-content">
                              <div class="modal-header bg-warning text-white">
                                  <h5 class="modal-title text-white" id="uploadConfirmLabel">Confirm Upload</h5>
                                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  Are you sure you want to upload this file?
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                  <button type="button" class="btn btn-warning" id="confirmUpload">Yes, Upload</button>
                              </div>
                          </div>
                      </div>
                    </div>


                <!-- Delete Button -->
                <!-- Delete Confirmation Modal -->
                @if($otpData->verification_by_sportsperson != "")
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                    Remove
                </button>
                <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title text-white" id="deleteConfirmLabel">Confirm Deletion</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to remove the file?
                            </div>
                            <div class="modal-footer">
                                <form method="POST" action="{{ route('file.remove') }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="applId" value="{{ $otpData->id }}">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Yes, Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @else                    
                @endif               
            </div>
            @if($otpData->verification_by_sportsperson != "")
            <h6 class="text-success mb-0"><a href="{{ url('storage/' . ($otpData->verification_by_sportsperson ?? '')) }}" target="_blank">View uploaded file</a></h6>
            @else                    
            @endif 
          </div>

            <!-- Submit Button -->
            
            <div>
              <form id="submitForm" action="{{ route('verify.status.submit') }}" method="POST">
                  @csrf
                  <input type="hidden" name="applId" value="{{ $otpData->id }}">
                  <input type="hidden" name="status" value="verified">

                  @if($otpData->verification_by_sportsperson != "")
                      <button type="button" class="btn btn-primary" id="submitTrigger">Submit</button>
                  @endif
              </form>

              <!-- Submit Confirmation Modal -->
              <div class="modal fade" id="submitConfirmModal" tabindex="-1" aria-labelledby="submitConfirmLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                          <div class="modal-header bg-primary text-white">
                              <h5 class="modal-title text-white" id="submitConfirmLabel">Confirm Submission</h5>
                              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              Are you sure you want to submit this application? You won't be able to edit it later.
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                              <button type="button" class="btn btn-primary" id="confirmSubmit">Yes, Submit</button>
                          </div>
                      </div>
                  </div>
              </div>
            </div>            
          </div>
        </div>
      </div>
      <!-- Action Buttons Ends -->
    </div>
  </div>

 
</div>

<style>
  #certificateCard{display: none;}
  .certificate-card {max-width:1000px;margin: auto;background: #fff;}
  .certificate-card h4{margin:0}
  .certificate-card h5 {font-size: 16px;margin: 0;color: #000;font-style: italic;}
  .certificate-card table table tr > td{padding: 10px;border:1px solid #eee}
  .certificate-card small {color: #5f788a;font-size: 12px; text-transform: uppercase;font-weight: 600;}
  .certificate-card ul {padding:0}
  .certificate-card ul li{list-style: none;}
  .certificate-card ul li i{color:#4bbc8e;width: 18px; }
  .certificate-card ul li span {display: inline-block;vertical-align: top;width: calc(100% - 27px);}
  .certificate-card ol li {width: 52%;display: inline-block;vertical-align: top;margin-bottom: 10px;}
  .certificate-card ol li:nth-child(2n+1){width: 40%}
  .certificate-card ol li i{ color: #225395; }
</style>

<!-- HIDDEN CERTIFICATE CARD -->
<div id="certificateCard">
  <div class="certificate-card">
    <table width="100%">
      <thead style="background: #225395;color:#fff;">
        <tr>
          <th style="padding:10px; display:flex; justify-content:space-between;align-items:center;">
            <div style="font-size:17px; font-weight: normal">
              <img id="logo" src="{{ url('assets/job_app/dash/images/logo-sports.png') }}" alt="Sports Haryana Govt" class="img-fluid" style="height: 80px;"> Application for Sports Gradation Certificate
            </div>
            <div>
              <p style="font-size:15px;margin:0 0 5px;color:#fff;font-weight: normal;">Application ID - <strong style="border-bottom: 1px dotted ">{{ $otpData->appl_id }}</strong></p>
              
            </div>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <table width="100%">
              <tr>
                <td>
                  <small>1. Name</small>
                  <h5>{{ $otpData->sports_person_name }}</h5>
                </td>
                <td>
                  <small>2. Aadhar No.</small>
                  <h5>{{ $otpData->aadhaar_no }}</h5>
                </td>
                <td>
                  <small>3. Mobile No.</small>
                  <h5>{{ $otpData->mobile_no }}</h5>
                </td>
                <td>
                  <small>4. District</small>
                  <h5>{{ $otpData->district_sportsperson_belongs }}</h5>
                </td>
                
                <td rowspan="2" width="19%">
                 <img src="{{ url('storage/' . ($otpData->profile_picture ?? 'default.jpg')) }}" width="140px" height="140px" style="border:5px solid #eee">
                </td>
              </tr>
              <tr>
                <td>
                  <small>5. Domicile State</small>
                  <h5>{{ $otpData->domicile_state }}</h5>
                </td>
                <td>
                  <small>6. State/Organization </small>
                  <h5>{{ $otpData->plays_for_statte_org }}</h5>
                </td>
                <td>
                  <small>7. Sports Discipline</small>
                  <h5>{{ $otpData->name_sports_discipline }}</h5>
                </td>
                <td>
                  <small>Type of Event</small>
                  <h5>{{ $otpData->type_of_event }}</h5>
                </td>                
              </tr>
          
              <tr>
                <td colspan="3" width="58%">
                   <small>Tournament</small>
                   <h5 style="width: 95%">{{ $otpData->tournament }}</h5>
                </td>
                <td width="58%">
                   <small>Name of the Tournament</small>
                   <h5 style="width: 95%">{{ $otpData->name_of_tournamentN }}</h5>
                </td>
                <td colspan="2">
                  <small>Organizing Authority</small> 
                  <h5 style="width: 95%">{{ $otpData->organising_authority }}</h5>
                </td>
              </tr>
              <tr>
                <td>
                  <small>Venue of Tournament</small>
                  <h5>{{ $otpData->venue_of_tournament }}</h5>
                </td>
                <td>
                  <small>Date</small>
                  <h5>{{ \Carbon\Carbon::parse($otpData->month_year)->format('d-m-Y') }}</h5>
                </td>       
                <td> 
                  <small>Tournament Type</small>
                  <h5>{{ $otpData->tournament_type }}</h5>
                </td>
             
                <td>
                  <small>Medal Won</small>
                  <h5>{{ $otpData->medal_won }}</h5>
                </td>
                <td> 
                  <small>Participation Level</small>
                  <h5>{{ $otpData->participation_level }}</h5>
                </td>                
                      
              </tr>
            </table>
          </td>
        </tr>
        <tr style="page-break-before: always;">
  <td style="padding:10px">
    <h4>Documents Attached</h4>

    <!-- Aadhaar Card -->
    <div style="page-break-after: always; text-align:center">
      <img src="{{ url('storage/' . ($otpData->aadhaar_card ?? 'default.jpg')) }}" 
           width="250" height="250" style="border:5px solid #eee;" />
      <h5 style="margin-top: 10px; font-weight: 500;font-size: 18px;">1. Aadhaar Card</h5>
    </div>

    <!-- Domicile Certificate -->
    <div style="page-break-after: always; text-align:center">
      <img src="{{ url('storage/' . ($otpData->domicile_certificate ?? 'default.jpg')) }}" 
           width="250" height="250" style="border:5px solid #eee;" />
      <h5 style="margin-top: 10px; font-weight: 500;font-size: 18px;">2. Domicile Certificate</h5>
    </div>

    <!-- Achievement Certificate -->
    <div style="page-break-after: always; text-align:center">
      <img src="{{ url('storage/' . ($otpData->sports_certificate ?? 'default.jpg')) }}" 
           width="250" height="250" style="border:5px solid #eee;" />
      <h5 style="margin-top: 10px; font-weight: 500;font-size: 18px;">3. Achievement Certificate</h5>
    </div>

    <!-- NOC Upload -->
    <div style="page-break-after: always; text-align:center">
      <img src="{{ url('storage/' . ($otpData->noc_upload ?? 'default.jpg')) }}" 
           width="250" height="250" style="border:5px solid #eee;" />
      <h5 style="margin-top: 10px; font-weight: 500;font-size: 18px;">4. NOC Upload</h5>
    </div>

    @if($otpData->more_than25_photo != "")
    <div style="page-break-after: always; text-align:center">
      <img src="{{ url('storage/' . ($otpData->more_than25_photo ?? 'default.jpg')) }}" 
           width="250" height="250" style="border:5px solid #eee;" />
      <h5 style="margin-top: 10px; font-weight: 500;font-size: 18px;">5. Certificate for Proof of Playing 25% Matches</h5>
    </div>
    @endif
  </td>
</tr>


        <tr>
          <td style="padding:10px; background:#eee">
            <h4>Declaration</h4>
            <ul>
              <li><i class="fa-solid fa-square-check"></i> <span>I certify that I am currently a domicile/resident of Haryana.</span></li>
              <li><i class="fa-solid fa-square-check"></i> <span>I certify that I have never played for any State or Union Territory other than Haryana.</span></li>
              <li><i class="fa-solid fa-square-check"></i> <span>I certify that I have played for Haryana at the National/International Level.</span></li>
              <li><i class="fa-solid fa-square-check"></i> <span>I certify that I have not been penalized for any unfair practice like age fraud, doping, etc., in the tournament for which cash award is being applied for.</span></li>
              <li><i class="fa-solid fa-square-check"></i> <span>I certify that I have enclosed the self-attested copies of the documents as per requirements.</span></li>
              <li><i class="fa-solid fa-square-check"></i> <span>I also understand that if any information provided by me for the grant of Gradation Certificate is found to be false or incorrect, then I shall be liable for any penal action.</span></li>
            </ul>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td style="padding: 20px;">
            <div style="width:250px; text-align:center">
              <span style="width: 100%;border-bottom: 1px dashed #9999;height: 1px;display: block;margin-top: 30px;margin-bottom: 10px;"></span>
              <h6 style="text-align:center">(Signature of Sportsperson)</h6>
            </div>
          </td>
          <td>
            <h6 style="margin-left: -173px;">Date - <strong>{{ \Carbon\Carbon::parse($otpData->date)->format('d-m-Y') }}</strong></h6>
          </td>
        </tr>
        
      </tfoot>
    </table>    
</div>
</div>

@endsection