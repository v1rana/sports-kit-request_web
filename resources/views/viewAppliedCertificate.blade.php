@extends('layouts.dashboard')

@section('title', 'Sports !! Dashborad')

@section('content')

<style>        
        .certificate {
            border: 2px solid #FFA500;
            padding: 20px;
            width: 800px;
            margin: auto;
            position: relative;
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
            border: 1px solid black;           
            float: right;
            
        }
        .footer {
            margin-top: 40px;
            text-align: center;
        }
        .print-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
		.underline {
            display: inline-block;
            border-bottom: 1px solid black;
            width: 200px;
        }
        
    </style>
<button class="print-button" onclick="window.print()">Print Certificate</button>
    <div class="certificate">
        <img src="{{ url('assets/job_app/images/gov_logo.webp') }}" alt="Logo" class="logo" style="position: absolute;top: 23px;left: 19px;width: 80px;">
        <div class="header">
            <p>Haryana Govt. Gazette</p>
            
        </div>
        <div class="content">
            <p style="text-align:end;margin-top: 60px;">Certificate No. <span class="underline"><strong> {{ $otpData->certificate_no ?? '' }} </strong></span></p>
			<p style="text-align:center;font-size: 21px;"><b>Sports Gradation Certificate</b></p>
            <div class="photo-box"><img id="img-upload" src="{{ url('storage/' . ($otpData->profile_picture ?? 'default.jpg')) }}" class="mb-1" /></div>
            <p>Name of Sportsperson:<span class="underline"><strong>{{ $otpData->sports_person_name ?? '' }}</strong></span></p>
            <p>Aadhaar No.: <span class="underline"><strong>{{ $otpData->aadhaar_no ?? '' }}</strong></span></p>
            <p>Mobile No.: <span class="underline"><strong>{{ $otpData->mobile_no ?? '' }}</strong></span></p>
            <p>District sportsperson belongs to: <span class="underline"><strong>{{ $otpData->district_sportsperson_belongs ?? '' }}</strong></span></p>
            <p>Domicile State: <span class="underline"><strong>{{ $otpData->domicile_state ?? '' }}</strong></span></p>
            <p>Plays for (State/Organization): <span class="underline"><strong>{{ $otpData->plays_for_statte_org ?? '' }}</strong></span></p>
            <p>Name of Sports Discipline: <span class="underline"><strong>{{ $otpData->name_sports_discipline ?? '' }}</strong></span></p>
            <b>Best Sports Achievement</b>
            <ul>
                <li style="padding: 5px;">Name of Tournament: <span class="underline" style="width: 313px;"><strong>{{ $otpData->tournament_name ?? '' }}</strong></span></li>
                <li style="padding: 5px;">Month & Year: <span class="underline"><strong>{{ $otpData->month_year ?? '' }}</strong></span></li>
                <li style="padding: 5px;">Venue of Tournament: <span class="underline"><strong>{{ $otpData->venue_of_tournament ?? '' }}</strong></span></li>
                <li style="padding: 5px;">Organizing Authority: <span class="underline"><strong>{{ $otpData->organising_authority ?? '' }}</strong></span></li>
                <li style="padding: 5px;">Tournament Type: <input type="checkbox" readonly value="Senior" {{ isset($otpData->tournament_type) && $otpData->tournament_type == 'Senior' ? 'checked' : '' }}> Senior <input type="checkbox"  value="Senior" {{ isset($otpData->tournament_type) && $otpData->tournament_type == 'Junior' ? 'checked' : '' }}> Junior</li>
                <li style="padding: 5px;">Medal Won: 
				<input type="checkbox"  value="Senior" {{ isset($otpData->medal_won) && $otpData->medal_won == 'Gold' ? 'checked' : '' }}>  Gold 
				<input type="checkbox" readonly value="Silver" {{ isset($otpData->medal_won) && $otpData->medal_won == 'Silver' ? 'checked' : '' }}> Silver 
				<input type="checkbox" readonly value="Bronze" {{ isset($otpData->medal_won) && $otpData->medal_won == 'Bronze' ? 'checked' : '' }}> Bronze</li>
                <li>Participation Level: 
				<input type="checkbox" readonly value="25% or more" {{ isset($otpData->participation_level) && $otpData->participation_level == '25% or more' ? 'checked' : '' }}> 25% or more 
				<input type="checkbox" readonly value="Less than 25%" {{ isset($otpData->participation_level) && $otpData->participation_level == 'Less than 25%' ? 'checked' : '' }}> Less than 25%</li>
            </ul>
            <p>Date: <span class="underline"><strong>{{ $otpData->created_at ?? '' }}</strong></span> <span style="margin-left: 115px;">Signature of Sportsperson:</span> <span class="underline"><strong>{{ $otpData->certificate_no ?? '' }}</strong></span></p>
            <p>Checked. A copy of supporting documents (self-attested) in support of the claim is retained in office.</p>
			<p>
    Date: <span class="underline"><strong>{{ $otpData->created_at ?? '' }}</strong></span> 
    <span style="margin-left: 74px;">Granted Grade:</span> 
    <span class="underline"><strong>{{ $otpData->certificate_no ?? '' }}</strong></span> Sports Certificate
</p>
			
        </div>
        <div class="footer">
            
            <p style="margin-left: -37%;">District Sports & Youth Affairs Officer: <span class="underline"><strong>{{ $otpData->certificate_no ?? '' }}</strong></span></p>
            
            <p><b>Department of Sports & Youth Affairs, Haryana</b></p>
        </div>
    </div>

@endsection