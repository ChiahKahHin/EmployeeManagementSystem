@extends('layouts.template')

@section('title')
    {{ Auth::user()->getRoleName() }} | Manage Leave Types
@endsection

@section('pageTitle')
    Manage Leave Types
@endsection

@section('content')
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">All Leave Types</h4>
        </div>
        <div class="pb-20">
            <table class="data-table table stripe hover nowrap">
                <thead>
                    <tr>
						<th>#</th>
                        <th>Leave Type</th>
                        <th>Leave Limit</th>
                        <th>Gender</th>
                        <th style="text-align: center;">Married Employee Only?</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
					@foreach ($leaveTypes as $leaveType)
                    <tr>
						<td>{{ $loop->iteration }}</td>
						<td class="table-plus">{{ ucwords($leaveType->leaveType) }}</td>
                        @if ($leaveType->leaveType == "Carried Forward Leave")
                            <td><i>(Each employee will be differ)</i></td>
                        @else
                            <td>{{ ucwords($leaveType->leaveLimit) }}</td>
                        @endif
                        <td>{{ ucwords($leaveType->gender) }}</td>
                        <td style="text-align: center;">{{ ($leaveType->maritalStatus == 1) ? "Yes" : "No" }}</td>
                        <td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									{{-- <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a> --}}
									<a class="dropdown-item" href="{{ route('editLeaveType', ['id' => $leaveType->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
									<a class="dropdown-item deleteLeaveType" id="{{ $leaveType->id }}" value="{{ ucwords($leaveType->leaveType) }}"><i class="dw dw-delete-3"></i> Delete</a>
								</div>
							</div>
						</td>
					</tr>
					@endforeach
                    
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', '.deleteLeaveType', function() {
            var leaveTypeId = $(this).attr('id');
            var leaveType = $(this).attr('value');
            swal({
                title: 'Delete this leave type?',
                text: 'Leave Type: ' + leaveType,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value){
                    swal({
                        title: "Deleted!",
                        text: leaveType + " leave type removed from system",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/deleteLeaveType/" + leaveTypeId;
                    });
                }
                else{
                    swal("Cancelled", "Leave type is not removed from system", "error");
                }
            });
        });
    </script>
    
@endsection