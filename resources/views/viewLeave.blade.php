@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | View Leave Details
@endsection

@section('pageTitle')
	View Leave Details
@endsection

@section('content')
	@if ($leaveRequest->leaveStatus <= 2)
		<div class="card">
			<div class="row d-flex justify-content-between top-progressbar3">
				<div class="d-flex pb-4">
					<h5>Leave Status</h5>
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<div class="col-12">
					<ul id="progressbar3" class="text-center">
						<li class="@if($leaveRequest->leaveStatus >= 0) active @endif step0" @if($leaveRequest->leaveStatus >= 0) style="cursor: pointer;" data-toggle="modal" data-target="#waiting-approval-modal" @endif></li>
						<li class="@if($leaveRequest->leaveStatus >= 1) active @endif step0" @if($leaveRequest->leaveStatus >= 1) style="cursor: pointer;" data-toggle="modal" data-target="#approved-rejected-modal" @endif></li>
						<li class="@if($leaveRequest->leaveStatus >= 1) active @endif step0" @if($leaveRequest->leaveStatus >= 1) style="cursor: pointer;" data-toggle="modal" data-target="#completed-modal" @endif></li>
					</ul>
				</div>
			</div>

			<div class="row justify-content-between top-progressbar3">
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Waiting<br>Approval</p>
					</div>
				</div>
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">@if($leaveRequest->leaveStatus < 1) Approved/Rejected @elseif($leaveRequest->leaveStatus == 1) Rejected @elseif($leaveRequest->leaveStatus == 2) Approved @endif</p>
					</div>
				</div>
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Completed<br></p>
					</div>
				</div>
			</div>
		</div>
	@elseif ($leaveRequest->leaveStatus <= 3)
		<div class="card">
			<div class="row d-flex justify-content-between top-progressbar2">
				<div class="d-flex pb-4">
					<h5>Leave Status</h5>
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<div class="col-12">
					<ul id="progressbar2" class="text-center">
						<li class="@if($leaveRequest->leaveStatus >= 0) active @endif step0" @if($leaveRequest->leaveStatus >= 0) style="cursor: pointer;" data-toggle="modal" data-target="#waiting-approval-modal" @endif></li>
						<li class="@if($leaveRequest->leaveStatus >= 3) active @endif step0" @if($leaveRequest->leaveStatus >= 3) style="cursor: pointer;" data-toggle="modal" data-target="#cancelled-modal" @endif></li>
					</ul>
				</div>
			</div>

			<div class="row justify-content-between top-progressbar2">
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Waiting<br>Approval</p>
					</div>
				</div>
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Cancelled<br></p>
					</div>
				</div>
			</div>
		</div>
	@else
		<div class="card">
			<div class="row d-flex justify-content-between top-progressbar3">
				<div class="d-flex pb-4">
					<h5>Leave Status</h5>
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<div class="col-12">
					<ul id="progressbar3" class="text-center">
						<li class="@if($leaveRequest->leaveStatus >= 0) active @endif step0" @if($leaveRequest->leaveStatus >= 0) style="cursor: pointer;" data-toggle="modal" data-target="#waiting-approval-modal" @endif></li>
						<li class="@if($leaveRequest->leaveStatus >= 1) active @endif step0" @if($leaveRequest->leaveStatus >= 1) style="cursor: pointer;" data-toggle="modal" data-target="#approved-modal" @endif></li>
						<li class="@if($leaveRequest->leaveStatus >= 4) active @endif step0" @if($leaveRequest->leaveStatus >= 4) style="cursor: pointer;" data-toggle="modal" data-target="#cancelled-after-approved-modal" @endif></li>
					</ul>
				</div>
			</div>

			<div class="row justify-content-between top-progressbar3">
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Waiting<br>Approval</p>
					</div>
				</div>
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Approved</p>
					</div>
				</div>
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Cancelled After <br> Approved</p>
					</div>
				</div>
			</div>
		</div>
	@endif

	{{-- Leave Request Modals --}}
	<div class="modal fade" id="waiting-approval-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Leave Waiting Approval</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This leave request is waiting for the manager approval. <br>
						An email notification will be sent for the employee once manager approve/reject it.
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="approved-rejected-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">
						@if ($leaveRequest->leaveStatus == 1)
							Leave Request Rejected
						@elseif ($leaveRequest->leaveStatus == 2)
							Leave Request Approved
						@endif
					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						@if ($leaveRequest->leaveStatus == 1)
							This leave request is rejected by the manager. <br>
							Kindly refer to the reason given by the manager and request for a new leave application.
						@elseif ($leaveRequest->leaveStatus == 2)
							This leave request is approved by the manager.
						@endif
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="approved-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">
						Leave Request Approved
					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This leave request is approved by the manager.
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="completed-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Leave Request Completed</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This leave request is completed.
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="cancelled-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Leave Request Cancelled</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This leave request is cancelled.
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="cancelled-after-approved-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Leave Request Cancelled</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This leave request is cancelled after approved by the manager.
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>


	{{-- Leave Details Table --}}
	<div class="pd-20 card-box mb-30">
		<div class="clearfix mb-20">
			<div class="pull-left">
				<h4 class="text-blue h4">Leave Details</h4>
			</div>
		</div>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th scope="col" width="40%">Leave Details</th>
					<th scope="col" width="60%">Leave Information</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="font-weight-bold">Leave Type</td>
					<td>{{ ucwords($leaveRequest->getLeaveType->leaveType) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Employee </td>
					<td>{{ $leaveRequest->getEmployee->getFullName() }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Manager </td>
					<td>{{ $leaveRequest->getManager->getFullName() }}</td>
				</tr>
				@if ($leaveRequest->delegateManagerID)
					<tr>
						<td class="font-weight-bold">Delegate Manager </td>
						<td>{{ $leaveRequest->getDelegateManager->getFullName() }}</td>
					</tr>
				@endif
				<tr>
					<td class="font-weight-bold">Leave Start Date</td>
					<td>{{ date("d F Y", strtotime($leaveRequest->leaveStartDate)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Leave End Date</td>
					<td>{{ date("d F Y", strtotime($leaveRequest->leaveEndDate)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Leave Duration <br> <i>(After deducting public holidays & Non-working day)</i></td>
					<td>{{ $leaveRequest->leaveDuration }}</td>
				</tr>				
				<tr>
					<td class="font-weight-bold">Leave Period</td>
					<td>{{ $leaveRequest->leavePeriod }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Leave Description</td>
					<td>{{ $leaveRequest->leaveDescription }}</td>
				</tr>
				@php
					if ($leaveRequest->leaveReplacement == 1)
						$leaveReplacement = "Yes";
					else
						$leaveReplacement = "No";
				@endphp
				<tr>
					<td class="font-weight-bold">Replacement Leave?</td>
					<td>{{ $leaveReplacement }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Leave Status</td>
					<td>{!! $leaveRequest->getStatus() !!}</td>
				</tr>
				@if ($leaveRequest->leaveRejectedReason != null)
					<td class="font-weight-bold">Leave Rejected Reason</td>
					<td>{{ $leaveRequest->leaveRejectedReason }}</td>
				@endif
				<tr>
					<td class="font-weight-bold">Leave Created Date & Time</td>
					<td>{{ date("d F Y, g:ia", strtotime($leaveRequest->created_at)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Leave Updated Date & Time</td>
					<td>{{ date("d F Y, g:ia", strtotime($leaveRequest->updated_at)) }}</td>
				</tr>
			</tbody>
		</table>
		@if (!Auth::user()->isEmployee())
			@if ($leaveRequest->leaveStatus == 0 && (Auth::user()->isAccess('admin', 'hrmanager') || $leaveRequest->managerID == Auth::id() || $leaveRequest->delegateManagerID == Auth::id()))
				<div class="row">
					<div class="col-md-6">
						<button type="button" id="approveLeaveRequest" class="btn btn-primary btn-block">Approve Leave Request</button>
					</div>
					<div class="col-md-6">
						<button type="button" data-toggle="modal" data-target="#login-modal" class="btn btn-primary btn-block">Reject Leave Request</button>
					</div>
				</div>
				@if (Auth::user()->isAccess('admin', 'hrmanager'))
					<br>
				@endif
			@endif
		@endif
		
		@if ($leaveRequest->employeeID == Auth::user()->id && ($leaveRequest->leaveStatus == 0 || ($leaveRequest->leaveStatus == 2 && $leaveRequest->leaveStartDate > date("Y-m-d"))))
			<div class="row">
				<div class="col-md-12">
					<button type="button" id="cancelLeaveRequest" class="btn btn-primary btn-block">Cancel Leave Request</button>
				</div>
			</div>
		@endif
		
		<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Reject Leave Request?</h2>
						</div>
						<div id="reasonTextInput" class="input-group custom">
							<input type="text" id="reasonOfRejectingLeaveRequest" class="form-control form-control-lg" placeholder="Reason of rejecting the leave request">
						</div>
						<div id="reasonErrorMessage"></div>
						<div class="row">
							<div class="col-sm-12">
								<div class="input-group mb-0">
									<button type="button" id="rejectLeaveRequest" class="btn btn-primary btn-lg btn-block">Reject Leave Request</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section("script")
	<script>
		$('#cancelLeaveRequest').on('click', function(){
			swal({
				title: "Cancel this leave request?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonClass: "btn btn-success",
				confirmButtonText: "Yes, cancel it!"
			}).then((result) => {
				if(result.value){
					swal({
                        title: "Cancelled!",
                        text: "Leave request cancelled",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/cancelLeaveRequest/" + {{ $leaveRequest->id }};
                    });
				}
				else{
					swal("Cancelled", "Leave request is not cancelled", "error");
				}
			});
		});

		$('#approveLeaveRequest').on('click', function(){
			swal({
				title: "Approve this leave request?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonClass: "btn btn-success",
				confirmButtonText: "Yes, approve it!"
			}).then((result) => {
				if(result.value){
					swal({
                        title: "Approved!",
                        text: "Leave request approved",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/approveLeaveRequest/" + {{ $leaveRequest->id }};
                    });
				}
				else{
					swal("Cancelled", "Leave request is not approved", "error");
				}
			});
		});
		
		var reason = document.getElementById('reasonOfRejectingLeaveRequest');
		reason.addEventListener("keyup", function(event){
			if(event.keyCode === 13){
				document.getElementById('rejectLeaveRequest').click();
			}
		});
		
		$('#rejectLeaveRequest').on('click', function(){
			if(reason.value != ""){
				document.getElementById('reasonTextInput').setAttribute("style", "margin-bottom: 25px");
				
				var reasonErrorMessage = document.getElementById('reasonErrorMessage');
				reasonErrorMessage.setAttribute("class", "");
				reasonErrorMessage.innerHTML = "";
				
				swal({
					title: "Reject this leave request?",
					text: "Reason: " + reason.value,
					type: 'warning',
					showCancelButton: true,
					confirmButtonClass: "btn btn-danger",
					confirmButtonText: "Reject it!"
				}).then((result) => {
					if(result.value){
						document.getElementById('login-modal').setAttribute('class', 'modal fade');
						swal({
							title: "Rejected!",
							text: "Leave request rejected",
							type: "success",
							showCancelButton: false,
							//timer:3000
						}).then(function(){
							window.location.href = "/rejectLeaveRequest/" + {{ $leaveRequest->id }} + '/' + escape(reason.value);
						});
					}
					else{
						swal("Cancelled", "Leave request is not rejected", "error");
					}
				});
			}
			else{
				document.getElementById('reasonTextInput').setAttribute("style", "margin-bottom: 0");
				
				var reasonErrorMessage = document.getElementById('reasonErrorMessage');
				reasonErrorMessage.setAttribute("class", "text-danger text-sm pb-3");
				reasonErrorMessage.innerHTML = "This field is required";
			}
		});
	</script>
@endsection