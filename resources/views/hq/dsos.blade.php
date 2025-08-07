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

<h4 class="">
    DSO List
    <a href="{{ route('hq.show_dso_form') }}" class="btn btn-success float-end ms-2">Add DSO</a>
    <a href="javascript:history.back()" class="btn btn-secondary float-end">
        <i class="fa-solid fa-arrow-left-long"></i> Back
    </a>
</h4>

<div class="bg-white shadow mb-5 p-3">
    <div class="table-responsive">
        <table class="table table-bordered bg-white table-hover">
            <thead>
                <tr class="bg-primary text-white">
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>District</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dsos as $index => $dso)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $dso->name }}</td>
                        <td>{{ $dso->email }}</td>
                        <td>{{ $dso->mob }}</td>
                        <td>{{ $dso->district }}</td>
                        <td>
                            <a href="{{ route('hq.edit_dso', $dso->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <!--<form action="{{ route('hq.delete_dso', $dso->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this DSO?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>-->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No DSOs found.</td>
                    </tr>
                @endforelse
            </tbody>
       </table>
							
			
	</div>	
	
</div>			
	
    </div>
	
    @endsection
   
