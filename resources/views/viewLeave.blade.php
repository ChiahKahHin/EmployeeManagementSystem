@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | View Leave Details
@endsection

@section('pageTitle')
	View Leave Details
@endsection

@section('content')
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
					<td class="font-weight-bold">Approval Manager </td>
					<td>{{ $leaveRequest->getManager->getFullName() }}</td>
				</tr>
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
					<td class="font-weight-bold">Repalcement Leave?</td>
					<td>{{ $leaveReplacement }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Leave Status</td>
					<td>{{ $leaveRequest->getStatus() }}</td>
				</tr>
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
		@if (Auth::user()->isAdmin() || Auth::user()->isHrManager())
			@if ($leaveRequest->leaveStatus == 0)
				<div class="row">
					<div class="col-md-6">
						<button type="button" id="approveLeaveRequest" class="btn btn-primary btn-block">Approve Leave Request</button>
					</div>
					<div class="col-md-6">
						<button type="button" data-toggle="modal" data-target="#login-modal" class="btn btn-primary btn-block">Reject Leave Request</button>
					</div>
				</div>
			@endif
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

	@if (!Auth::user()->isEmployee() && ($leaveRequest->leaveStatus == 0) && Auth::user()->id == $leaveRequest->manager)
		<div class="pd-20 card-box mb-30">
			<div class="clearfix">
				<div class="pull-left mb-10">
					<h4 class="text-blue h4">Leave Approval Manager Delegation?</h4>
				</div>
			</div>
			
			<form action="{{ route('changeLeaveManager', ['id' => $leaveRequest->id]) }}" method="POST">
				@csrf
				
	
				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<label>Other Manager</label>
							<select class="form-control selectpicker @error('manager') form-control-danger @enderror" id="manager" name="manager" onchange="checkManager();" required>
								@foreach ($managers as $manager)
									<option value="{{ $manager->id }}" {{ ($leaveRequest->manager == $manager->id ? "selected": null) }}>{{ $manager->getFullName($manager->id) }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
	
				<div class="row">
					<div class="col-md-6">
						<button type="submit" id="changeApprovingManagerBtn" class="btn btn-primary btn-block" disabled>Change Approval Manager</button>
					</div>
				</div>
			</form>
		</div>
	@endif
@endsection

@section("script")
	<script>
		function checkManager() 
		{  
			var manager = document.getElementById('manager');
			var managerID = manager.options[manager.selectedIndex].value;
			var originalManagerID = {{ $leaveRequest->manager }};
			if(managerID == originalManagerID){
				$("#changeApprovingManagerBtn").attr('disabled', true);
			}
			else{
				$("#changeApprovingManagerBtn").attr('disabled', false);
			}
		}

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
                        timer: 3000
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
							timer: 3000
						}).then(function(){
							window.location.href = "/rejectLeaveRequest/" + {{ $leaveRequest->id }} + '/' + reason.value;
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