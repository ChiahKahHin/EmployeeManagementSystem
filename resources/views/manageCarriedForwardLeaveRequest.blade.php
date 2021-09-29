@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Manage Carried Forward Leave Request
@endsection

@section('pageTitle')
	Manage Carried Forward Leave Request
@endsection

@section('content')
	<div class="card-box mb-30">
		<div class="pd-20">
			<h4 class="text-blue h4">All Carried Forward Leave Requests</h4>
		</div>
		<div class="pb-20">
			<table class="data-table table stripe hover nowrap">
				<thead>
					<tr>
						<th width="5%">#</th>
						<th>Manager</th>
						<th>Employee</th>
						<th>Number of days</th>
						<th>Status</th>
						<th class="datatable-nosort">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($carriedForwardLeaves as $carriedForwardLeave)
					<tr>
						<td>{{ $loop->iteration }}</td>
						@if ($carriedForwardLeave->managerID != null)
							<td class="table-plus">{{ $carriedForwardLeave->getManager->getFullName() }}</td>	
						@else
							<td class="table-plus">N/A</td>							
						@endif
						<td>{{ $carriedForwardLeave->getEmployee->getFullName() }}</td>
						<td>{{ $carriedForwardLeave->leaveLimit }}</td>
						<td>{!! $carriedForwardLeave->getStatus() !!}</td>
						<td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									<a class="dropdown-item" href="{{ route('viewCarriedForwardLeave', ['id' => $carriedForwardLeave->id]) }}"><i class="dw dw-eye"></i> View</a>
									@if (Auth::user()->isAdmin())
										<a class="dropdown-item deleteCFRequest" id="{{ $carriedForwardLeave->id }}" value="{{ $carriedForwardLeave->getEmployee->getFullName() }}"><i class="dw dw-delete-3"></i> Delete</a>
									@endif
								</div>
							</div>
						</td>
					</tr>
					@endforeach
					
				</tbody>
			</table>
		</div>
	</div>
	@if (session('message'))
		<script>
			swal({
				title: '{{ session("message") }}',
				type: 'success',
				confirmButtonClass: 'btn btn-success',
				//timer:5000
			});
		</script>
	@endif
@endsection

@section('script')
    <script>
        $(document).on('click', '.deleteCFRequest', function() {
            var CFRequestID = $(this).attr('id');
            var employeeName = $(this).attr('value');
            swal({
                title: 'Delete this carried forward leave request?',
                text: 'Employee: ' + employeeName,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value){
                    swal({
                        title: "Deleted!",
                        text: "C/F Request removed from system",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/deleteCFRequest/" + CFRequestID;
                    });
                }
                else{
                    swal("Cancelled", "C/F Request is not removed from system", "error");
                }
            });
        });
    </script>
    
@endsection