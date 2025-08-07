@extends('hq_main')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<h4 class="mb-3">Edit DSO
    <a href="{{ route('hq.dso') }}" class="btn btn-secondary float-end"><i class="fa-solid fa-arrow-left-long"></i> Back</a>
</h4>

<div class="bg-white shadow p-4 mb-5">
    <form action="{{ route('hq.update_dso', $dso->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">DSO Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $dso->name) }}" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" value="{{ old('email', $dso->email) }}" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                <input type="text" name="mobile" value="{{ old('mobile', $dso->mob) }}" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
    <label for="district" class="form-label">District <span class="text-danger">*</span></label>
    <select name="district" class="form-select" required>
        <option value="">Select District</option>
		
        @foreach($districts as $district)
            <option value="{{ $district->name }}"
                {{ old('district', $dso->district) == $district->name ? 'selected' : '' }}>
                {{ $district->name }}
            </option>
        @endforeach
    </select>
</div>

        </div>

        <button type="submit" class="btn btn-success">Update DSO</button>
    </form>
</div>
</div>
</div>
@endsection
