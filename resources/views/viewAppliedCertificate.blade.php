@extends('layouts.dashboard')

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
        .achievement-details li .underline{width:calc(100% - 12em);float:right}
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
        }
        
    </style>
    <div class="btn-area">
    <button onclick="window.print()" class="print-button btn btn-success float-end">Print Certificate</button>
    </div><div class="certificate">
        <p>Serial No: 4678</p>
        <img src="{{ url('assets/job_app/images/gov_logo.webp') }}" alt="Logo" class="logo" style="position: absolute;top: 60px;left: 19px;width: 80px;">
        <div class="header">
            <h2 class="text-capitalize">Sports Department, Haryana</h2>
            <h5 class="text-capitalize">Schedule - 1</h5>
            <h5 class="text-center">Sports Gradation Certificate</h5>
            
        </div>
        <div class="content">
            <p style="text-align:end;margin-top:30px;">Certificate No. <strong class="underline"> {{ $otpData->certificate_no ?? '' }} </strong></p>
			
            <table width="100%" class="basic-details">
                <tr>
                    <td>
                        <table width="100%">
                            <tr>
                                <td style="padding:5px 0px;">1. Name of Sportsperson: <strong class="underline">{{ $otpData->sports_person_name ?? '' }}</strong></td>
                            </tr>
                            <tr>
                                <td style="padding:5px 0px;">2. Aadhaar No.: <strong class="underline">{{ $otpData->aadhaar_no ?? '' }}</strong></td>
                            </tr>
                                <td style="padding:5px 0px;">3. Mobile No.: <strong class="underline">{{ $otpData->mobile_no ?? '' }}</strong></td>
                            </tr>
                            <tr>
                                <td style="padding:5px 0px;">4. District sportsperson belongs to: <strong class="underline">{{ $otpData->district_sportsperson_belongs ?? '' }}</strong></td>
                            </tr>
                            <tr>
                                <td style="padding:5px 0px;">5. Domicile State:<strong class="underline">{{ $otpData->domicile_state ?? '' }}</strong></td>
                            </tr>
                            <tr>
                                <td style="padding:5px 0px;">6. Plays for (Name of State/Organization): <strong class="underline">{{ $otpData->plays_for_statte_org ?? '' }}</strong></td>
                            </tr>
                            <tr>
                                <td style="padding:5px 0px;">7. Name of Sports Discipline: <strong class="underline">{{ $otpData->name_sports_discipline ?? '' }}</strong></td>
                            </tr>
                            <tr>
                                <td style="padding:5px 0px;">8. Best Sports Achievement </td>
                                <td style="padding:5px 0px;">Type Of Events :- Team </td>
                            </tr>
                        </table>
                    </td>
                    <td><div class="photo-box"><img id="img-upload" src="{{ url('storage/' . ($otpData->profile_picture ?? 'default.jpg')) }}" /></div></td>
                </tr>

    </table>
    
            <h6></h6>
            <ul class="achievement-details">
                <li style="padding: 5px;display: block;">i). Name of Tournament: <strong class="underline">{{ $otpData->tournament_name ?? '' }}</strong></li>
                <li style="padding: 5px;display: block;">ii). Date of Event: <strong class="underline">{{ $otpData->month_year ?? '' }}</strong></li>
                <li style="padding: 5px;display: block;">iii). Venue of Tournament: <strong class="underline">{{ $otpData->venue_of_tournament ?? '' }}</strong></li>
                <li style="padding: 5px;display: block;">iv). Organizing Authority: <strong class="underline">{{ $otpData->organising_authority ?? '' }}</strong></li>
                <li style="padding: 5px;display: block;">v). Tournament Type: <strong class="underline">{{$otpData->tournament_type}}</strong></li>
                <li style="padding: 5px;display: block;">vi). Medal Won: <strong class="underline">{{$otpData->medal_won}}</strong></li>
				<li style="padding: 5px;display: block;">vii). Participation Level: <strong class="underline">{{$otpData->participation_level}}</strong></li>
				
            </ul>
            <table class="signature-date-area">
                <tr>
                    <td>Date of form submission <strong class="underline">{{ $otpData->created_at ?? '' }}</strong></td>
                    <!--td> <p> <span style="margin-left: 115px;">Signature of Sportsperson:</span> <span class="underline"><strong>{{ $otpData->certificate_no ?? '' }}</strong></span></p></td-->
                </tr>
            </table>
           
            <!--p>Checked. A copy of supporting documents (self-attested) in support of the claim is retained in office.</p-->
			<table width="100%">
                <tr>
                    <td> Date of Issuance:<strong class="underline">{{ $otpData->created_at ?? '' }}</strong></td>
                    <td style="text-align:right">Granted Grade <strong class="underline" style="width: 50px; text-align:center; padding:0">A</strong> Sports Certificate</td>
                </tr>
            </table>
        </div>
        <table width="100%" class="footer">
            <tr>
                <td align="left">Deputy Director Sports : <strong class="underline"></strong></td>
                
                
                <td align="right">Director, Sports : <strong class="underline"></strong></td>
            </tr>
        </table>            
            <h6>Department of Sports Department, Haryana</h6>
        </div>
    </div>

@endsection