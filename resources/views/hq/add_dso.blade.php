@extends('hq_main')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<style>
	    label.info-label,small.info-label {font-weight: 500;font-size:17px;display: block; margin-bottom:0; line-height:normal}
	    .modal-body h5{margin:0; margin-left: 16px;font-size: 0.9rem; font-weight: 550;}
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

.table td{vertical-align:top}
.app-id-view-btn {
    border-width: 0 0 1px;
    text-align: left;
    border-style: dotted;
    width: auto;
    margin-bottom: 2px;
    white-space: nowrap;
    border-color: blue;
    font-weight: bold;
}
  .table .btn{font-size: 15px;padding:4px 0 0 0;    margin-top: 2px;}
  ul.list-unstyled li:before {
    position: absolute;
    content: "";
    background: rgba(0, 0, 0, 0.7);
	border-radius: 100%;
    width: 4px;
    height: 4px;
    top: 8px;
    left: -6px;
}
ul.list-unstyled li {
    position: relative;
}
</style>

<h4 class="">Add New DSO 
    <a href="{{ route('hq.dso') }}" class="btn btn-secondary float-end">
        <i class="fa-solid fa-arrow-left-long"></i> Back
    </a>
</h4>

<div class="bg-white shadow mb-5 p-3">
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following errors:
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('hq.create') }}" method="POST">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label">DSO Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
        </div>

        <div class="row mb-3">
           <div class="col-md-6">
			<label for="district" class="form-label">District <span class="text-danger">*</span></label>
			<select name="district" class="form-select" required>
				<option value="">-- Select District --</option>
				@foreach($districts as $district)
					<option value="{{ $district->name }}" {{ old('district') == $district->name ? 'selected' : '' }}>
						{{ $district->name }}
					</option>
				@endforeach
			</select>
		</div>

            <div class="col-md-6">
                <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                <input type="text" name="mobile" class="form-control" maxlength="10" pattern="[0-9]{10}" value="{{ old('mobile') }}" required>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Save DSO</button>
    </form>
</div>
</div>
</div>

@endsection

