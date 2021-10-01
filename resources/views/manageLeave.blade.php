@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Manage Leave
@endsection

@section('pageTitle')
	Manage Leave
@endsection

@section('content')
	<div class="card-box mb-30">
		<div class="pd-20">
			<h4 class="text-blue h4 pb-2">All Leave
				<a href="{{ route('applyLeave') }}" style="float: right" class="btn btn-outline-primary">
                    <i class="icon-copy dw dw-add"></i> Apply Leave
                </a>
			</h4>
			<h6 class="text-blue h6">Leave Status</h6>
			@php
				if(Auth::user()->isAccess('admin', 'hrmanager', 'manager')){
					$statuses = array("To be approve", "Waiting Approval", "Rejected", "Approved", "Cancelled", "Cancelled after approved");
				}
				else{
					$statuses = array("Waiting Approval", "Rejected", "Approved", "Cancelled", "Cancelled after approved");
				}
			@endphp
			<select class="w-25 selectpicker" id="status" onchange="changeStatus();">
				<option value="">All Leave Status</option>
				@foreach ($statuses as $status)
					<option value="{{ $status }}">{{ $status }}</option>
				@endforeach
			</select>
		</div>
		<div class="pb-20">
			<table class="data-table table stripe hover nowrap">
				<thead>
					<tr>
						<th>#</th>
						<th>Leave Type</th>
						@if (!Auth::user()->isEmployee())
							<th>Employee</th>
						@endif
						<th>Start Date</th>
						<th>End Date</th>
						<th>Status</th>
						<th class="datatable-nosort">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($leaveRequests as $leaveRequest)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td>{{ $leaveRequest->getLeaveType->leaveType }}</td>
						@if (!Auth::user()->isEmployee())
							<td class="table-plus">{{ $leaveRequest->getEmployee->getFullName() }}</td>
						@endif
						<td>{{ $leaveRequest->leaveStartDate }}</td>
						<td>{{ $leaveRequest->leaveEndDate }} </td>
						<td>{!! $leaveRequest->getStatus() !!}</td>
						<td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									<a class="dropdown-item" href="{{ route('viewLeave', ['id' => $leaveRequest->id]) }}"><i class="dw dw-eye"></i> View</a>
									{{-- <a class="dropdown-item" href="{{ route('editEmployee', ['id' => $leaveRequest->id]) }}"><i class="dw dw-edit2"></i> Edit</a> --}}
									@if (Auth::user()->isAdmin())
										<a class="dropdown-item deleteLeave" id="{{ $leaveRequest->id }}" value="{{ $leaveRequest->getEmployee->getFullName() }}" data-leaveType="{{ $leaveRequest->getLeaveType->leaveType }}"><i class="dw dw-delete-3"></i> Delete</a>
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
		function changeStatus(){
			table = $(".table").dataTable();
			let value = document.getElementById('status').value;
			@if (!Auth::user()->isEmployee())
				table.fnFilter(value, 5, false, true, true, true);
			@else
				table.fnFilter(value, 4, false, true, true, true);
			@endif
		}

        $(document).on('click', '.deleteLeave', function() {
            var leaveID = $(this).attr('id');
            var leaveUsername = $(this).attr('value');
            var leaveType = document.getElementById(leaveID).getAttribute('data-leaveType');
			
            swal({
                title: 'Delete this leave request?',
                html: 'Employee: ' + leaveUsername + '<br>' + 'Leave type: ' + leaveType,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value){
                    swal({
                        title: "Deleted!",
                        text: "Leave request removed from system",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/deleteLeave/" + leaveID;
                    });
                }
                else{
                    swal("Cancelled", "Leave request is not removed from system", "error");
                }
            });
        });
    </script>
    
@endsection