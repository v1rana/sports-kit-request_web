@extends('layouts.cerificate_main')

@section('title', 'Sports !! Dashborad')

@section('content')

<style>        
        .certificate {
            box-shadow:0 0 1.5rem rgba(0,0,0,0.1);
            padding: 20px;
            max-width: 800px;
            width:100%;
            margin: 0 auto 4em;
            position: relative;clear:both;
        }
        .header {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            text-transform: uppercase;
        }
        .content {
            margin-top: 20px;
        }
        .photo-box {
            border: 2px solid #eee;           
            float: right;
            
        }
        .content p{margin-bottom: 0.5em}
        .footer {
            margin-top: 40px;
            text-align: center;
        }
        .print-button {
            float:right;
            margin: 20px auto;
            padding: 10px 20px;
        }
		.underline {
            display: inline-block;
            border-bottom: 1px dotted black;
            width: 200px;
            margin-left:10px; padding-left:10px;
        }
        table.basic-details .underline{
    display: inline-block;
    border-bottom: 1px dotted black;
    width: calc(100% - 16em);
    margin-left: 10px;
    padding-left: 10px;
    float: right;
}
.btn-area{width:100%; max-width: 800px;margin:0 auto;}
        .achievement-details li{float:left; width:100%}
        .achievement-details li .underline{width:calc(100% - 11em);float:right}
        .signature-date-area{margin: 2em 0;float:left; width:100%}
         .footer td{vertical-align:top;}
         .footer + h6 {border-top: 1px solid #ddd;text-transform:uppercase;text-align: center;padding: 20px 0 0; margin: 28px 0 0;font-size: 18px;}
        .footer img{width:100px;  height: 100px;}
        .footer strong.underline {
    padding: 0;
    margin: 0;
}
         @media print{
            body{padding:0; margin:0}
            header, footer, .print-button{display:none}
            .certificate{box-shadow:0 0;}
			.hide-when-print {
        display: none !important;
    }
        }
        
    </style>
	
   <a href="{{ url()->previous() }}" class="btn btn-secondary float-end hide-when-print">
    <i class="fa-solid fa-arrow-left-long"></i> Back
</a>

    <div class="btn-area">
    <button onclick="window.print()" class="print-button btn btn-success float-end">Print Certificate</button>
    </div><div class="certificate">
        <img src="{{ url('assets/job_app/images/gov_logo.webp') }}" alt="Logo" class="logo" style="position: absolute;top: 23px;left: 19px;width: 80px;">
        <div class="header">
            <h2 class="text-capitalize">Sports Department</h2>
            
        </div>
        <div class="content">
            <p style="text-align:end;margin-top: 60px;">Certificate No. <strong class="underline"> {{ $otpData->certificate_no ?? '' }} </strong></p>
			<h5 class="text-center">Sports Gradation Certificate</h5>
            <table width="100%" class="basic-details">
                <tr>
                    <td>
                        <table width="100%">
                            <tr>
                                <td> Name of Sportsperson: <strong class="underline">{{ $otpData->sports_person_name ?? '' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Aadhaar No.: <strong class="underline">{{ $otpData->aadhaar_no ?? '' }}</strong></td>
                            </tr>
                                <td>Mobile No.: <strong class="underline">{{ $otpData->mobile_no ?? '' }}</strong></td>
                            </tr>
                            <tr>
                                <td>District sportsperson belongs to: <strong class="underline">{{ $otpData->district_sportsperson_belongs ?? '' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Domicile State:<strong class="underline">{{ $otpData->domicile_state ?? '' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Plays for (State/Organization): <strong class="underline">{{ $otpData->plays_for_statte_org ?? '' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Name of Sports Discipline: <strong class="underline">{{ $otpData->name_sports_discipline ?? '' }}</strong></td>
                            </tr>
                        </table>
                    </td>
                    <td><div class="photo-box"><img id="img-upload" src="{{ $otpData->profile_picture 
                    ? url('storage/' . $otpData->profile_picture) 
                    : asset('assets/job_app/images/user.png') }}"   /></div></td>
                </tr>

    </table>
    
            <h6>Best Sports Achievement</h6>
            <ul class="achievement-details">
                <li style="padding: 5px;">Name of Tournament: <strong class="underline">{{ $otpData->tournament ?? '' }}</strong></li>
                <li style="padding: 5px;">Month & Year: <strong class="underline">{{ $otpData->month_year ?? '' }}</strong></li>
                <li style="padding: 5px;">Venue of Tournament: <strong class="underline">{{ $otpData->venue_of_tournament ?? '' }}</strong></li>
                <li style="padding: 5px;">Organizing Authority: <strong class="underline">{{ $otpData->organising_authority ?? '' }}</strong></li>
                <li style="padding: 5px;">Tournament Type: <strong class="underline">{{$otpData->tournament_type}}</strong></li>
                <li style="padding: 5px;">Medal Won: <strong class="underline">{{$otpData->medal_won}}</strong></li>
				<li>Participation Level: <strong class="underline">{{$otpData->participation_level}}</strong></li>
				
            </ul>
            <table class="signature-date-area">
                <tr>
                    <td>Date of form submittion <strong class="underline">{{ $otpData->created_at ?? '' }}</strong></td>
                    <!--td> <p> <span style="margin-left: 115px;">Signature of Sportsperson:</span> <span class="underline"><strong>{{ $otpData->certificate_no ?? '' }}</strong></span></p></td-->
                </tr>
            </table>
           
            <!--p>Checked. A copy of supporting documents (self-attested) in support of the claim is retained in office.</p-->
			<table width="100%">
                <tr>
                    <td> Date:<strong class="underline">{{ $otpData->created_at ?? '' }}</strong></td>
                    <td style="text-align:right">Granted Grade <strong class="underline" style="width: 50px; text-align:center; padding:0">A</strong> Sports Certificate</td>
                </tr>
            </table>
        </div>
        <table width="100%" class="footer">
            <tr>
                <td align="left">District Sports Officer<strong class="underline">Sh. Satender Kumar</strong></td>
                <!--<td align="center"><img src="http://127.0.0.1:8000/storage/uploads/j7GfE0zaNbVCTGDFqmu537j4ZfJEMMYPd5tlaspg.jpg" /></td>-->
                
                <td align="right">Director, Sports<strong class="underline">Sh. Sanjeev Verma, IAS</strong></td>
            </tr>
        </table>            
            <h6>Sports Department</h6>
        </div>
    </div>

@endsection