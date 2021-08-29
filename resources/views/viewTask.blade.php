@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | View Task Details
@endsection

@section('pageTitle')
	View Task Details
@endsection

@section('content')
	{{-- <div class="page-header">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<div class="title">
					<h4>Task Details</h4>
				</div>
				<nav aria-label="breadcrumb" role="navigation">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('manageTask') }}">Manage Task</a></li>
						<li class="breadcrumb-item active" aria-current="page">View task details</li>
					</ol>
				</nav>
				
			</div>
		</div>
	</div> --}}
	<div class="card">
		<div class="row d-flex justify-content-between top-progressbar4">
			<div class="d-flex pb-4">
				<h5>Task Status</h5>
			</div>
		</div>
		<div class="row d-flex justify-content-center">
			<div class="col-12">
				<ul id="progressbar4" class="text-center">
					<li class="@if($task->status >= 0) active @endif step0" @if($task->status >= 0) style="cursor: pointer;" data-toggle="modal" data-target="#pending-modal" @endif></li>
					<li class="@if($task->status >= 1) active @endif step0" @if($task->status >= 1) style="cursor: pointer;" data-toggle="modal" data-target="#waiting-approval-modal" @endif></li>
					<li class="@if($task->status >= 2) active @endif step0" @if($task->status >= 2) style="cursor: pointer;" data-toggle="modal" data-target="#approved-rejected-modal" @endif></li>
					<li class="@if($task->status >= 3) active @endif step0" @if($task->status >= 3) style="cursor: pointer;" data-toggle="modal" data-target="#completed-modal" @endif></li>
				</ul>
			</div>
		</div>

		<div class="row justify-content-between top-progressbar4">
			<div class="row d-flex icon-content">
				<div class="d-flex flex-column">
					<p class="font-weight-bold">Pending</p>
				</div>
			</div>
			<div class="row d-flex icon-content">
				<div class="d-flex flex-column">
					<p class="font-weight-bold">Waiting Approval</p>
				</div>
			</div>
			<div class="row d-flex icon-content">
				<div class="d-flex flex-column">
					<p class="font-weight-bold">@if($task->status < 2) Approved/Rejected @elseif($task->status == 2) Rejected @elseif($task->status == 3) Approved @endif</p>
				</div>
			</div>
			<div class="row d-flex icon-content">
				<div class="d-flex flex-column">
					<p class="font-weight-bold">Completed<br></p>
				</div>
			</div>
		</div>
	</div>

	{{-- Task Modals --}}
	<div class="modal fade" id="pending-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Task Pending</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						Once the task is completed, kindly click on the complete task button below. <br>
						An email notification will be sent for the manager to approve/reject the task. <br>
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="waiting-approval-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Task Waiting Approval</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This task is waiting for the manager approval. <br>
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
						@if ($task->status == 2)
							Task Rejected
						@elseif ($task->status == 3)
							Task Approved
						@endif
					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						@if ($task->status == 2)
							This task is rejected by the manager. <br>
							Kindly refer to the reason given by the manager and make the changes. <br>
							Once changes is made, you can request for approval again.
						@elseif ($task->status == 3)
							This task is approved by the manager.
						@endif
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
					<h4 class="modal-title" id="myLargeModalLabel">Task Completed</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This task is completed.
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	{{-- Task Details Tables --}}
	<div class="pd-20 card-box mb-30">
		<div class="clearfix mb-20">
			<div class="pull-left">
				<h4 class="text-blue h4">Task Details</h4>
			</div>
		</div>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th scope="col" width="30%">Task Details</th>
					<th scope="col" width="70%">Task Information</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="font-weight-bold">Title</td>
					<td>{{ ucwords($task->title) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Description</td>
					<td>{{ ucfirst($task->description) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Manager</td>
					<td>{{ $task->getManager->getFullName() }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Person In Charge</td>
					<td>{{ $task->getPersonInCharge->getFullName() }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Priority</td>
					<td>{{ $task->priority }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Due Date</td>
					<td>{{ date("d F Y", strtotime($task->dueDate)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Status</td>
					<td>{{ $task->getStatus() }}</td>
				</tr>
				@if (count($task->getRejectedReasons) > 0)
					<tr>
						<td class="font-weight-bold">Number of times task being rejected</td>
						<td>{{ count($task->getRejectedReasons) }}</td>
					</tr>
					<tr>
						<td class="font-weight-bold">Task Rejected Reasons (Date & Time)</td>
						<td>
							<ol>
								@foreach ($task->getRejectedReasons as $rejectedReason)
									<li>
										{{ $loop->iteration }}. {{ $rejectedReason->rejectedReason }} ({{ date("d F Y, g:ia", strtotime($rejectedReason->created_at)) }})
									</li>
								@endforeach
							</ol>
						</td>
					</tr>
				@endif
				{{-- @if (count($rejectedReasons) > 0)
					<tr>
						<td class="font-weight-bold">Number of times task being rejected</td>
						<td>{{ count($rejectedReasons) }}</td>
					</tr>
					<tr>
						<td class="font-weight-bold">Task Rejected Reasons (Date & Time)</td>
						<td>
							<ol>
								@foreach ($rejectedReasons as $rejectedReason)
									<li>
										{{ $loop->iteration }}. {{ $rejectedReason->rejectedReason }} ({{ date("d F Y, g:ia", strtotime($rejectedReason->created_at)) }})
									</li>
								@endforeach
							</ol>
						</td>
					</tr>
				@endif --}}
				<tr>
					<td class="font-weight-bold">Task Created Date & Time</td>
					<td>{{ date("d F Y, g:ia", strtotime($task->created_at)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Task Updated Date & Time</td>
					<td>{{ date("d F Y, g:ia", strtotime($task->updated_at)) }}</td>
				</tr>
			</tbody>
		</table>
		@if (!Auth::user()->isEmployee())
			@if ($task->status == 1)
				<div class="row">
					<div class="col-md-6">
						<button type="button" id="approveTask" class="btn btn-primary btn-block">Approve Task</button>
					</div>
					<div class="col-md-6">
						<button type="button" data-toggle="modal" data-target="#login-modal" class="btn btn-primary btn-block">Reject Task</button>
					</div>
				</div>
			@endif
		@endif

		@if (Auth::user()->isEmployee())
			@if ($task->status == 0 || $task->status == 2)
				<div class="row">
					<div class="col-md-12">
						<button type="button" id="completeTask" class="btn btn-primary btn-block">Complete Task</button>
					</div>
				</div>
			@endif
		@endif
		
		<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Reject Task?</h2>
						</div>
						<div id="reasonTextInput" class="input-group custom">
							<input type="text" id="reasonOfRejectingTask" class="form-control form-control-lg" placeholder="Reason of rejecting the task">
						</div>
						<div id="reasonErrorMessage"></div>
						<div class="row">
							<div class="col-sm-12">
								<div class="input-group mb-0">
									<button type="button" id="rejectTask" class="btn btn-primary btn-lg btn-block">Reject Task</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@if (!Auth::user()->isEmployee() && ($task->status == 0 || $task->status == 1))
		<div class="pd-20 card-box mb-30">
			<div class="clearfix">
				<div class="pull-left mb-10">
					<h4 class="text-blue h4">Task Approval Manager Delegation?</h4>
				</div>
			</div>
			
			<form action="{{ route('changeTaskManager', ['id' => $task->id]) }}" method="POST">
				@csrf
				
	
				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<label>Other Manager</label>
							<select class="form-control selectpicker @error('manager') form-control-danger @enderror" id="manager" name="manager" onchange="checkManager();" required>
								@foreach ($managers as $manager)
									<option value="{{ $manager->id }}" {{ ($task->manager == $manager->id ? "selected": null) }}>{{ $manager->getFullName() }}</option>
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
			var originalManagerID = {{ $task->manager }};
			if(managerID == originalManagerID){
				$("#changeApprovingManagerBtn").attr('disabled', true);
			}
			else{
				$("#changeApprovingManagerBtn").attr('disabled', false);
			}
		}
		$('#approveTask').on('click', function(){
			swal({
				title: "Approve this task?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonClass: "btn btn-success",
				confirmButtonText: "Yes, approve it!"
			}).then((result) => {
				if(result.value){
					swal({
                        title: "Approved!",
                        text: "Task approved",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/approveTask/" + {{ $task->id }};
                    });
				}
				else{
					swal("Cancelled", "Task is not approved", "error");
				}
			});
		});
		
		var reason = document.getElementById('reasonOfRejectingTask');
		reason.addEventListener("keyup", function(event){
			if(event.keyCode === 13){
				document.getElementById('rejectTask').click();
			}
		});
		
		$('#rejectTask').on('click', function(){
			if(reason.value != ""){
				document.getElementById('reasonTextInput').setAttribute("style", "margin-bottom: 25px");
				
				var reasonErrorMessage = document.getElementById('reasonErrorMessage');
				reasonErrorMessage.setAttribute("class", "");
				reasonErrorMessage.innerHTML = "";
				
				swal({
					title: "Reject this task?",
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
							text: "Task rejected",
							type: "success",
							showCancelButton: false,
							//timer:3000
						}).then(function(){
							window.location.href = "/rejectTask/" + {{ $task->id }} + '/' + reason.value;
						});
					}
					else{
						swal("Cancelled", "Task is not rejected", "error");
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

		$('#completeTask').on('click', function(){
			swal({
				title: "Complete this task?",
				text: "An email will be sent to manager for task verification purpose",
				type: 'warning',
				showCancelButton: true,
				confirmButtonClass: "btn btn-success",
				confirmButtonText: "Submit"
			}).then((result) => {
				if(result.value){
					swal({
						title: "Submitted!",
						text: "An email will be sent back once the verification is done",
						type: "success",
						showCancelButton: false,
						//timer:3000
					}).then(function(){
						window.location.href = "/completeTask/" + {{ $task->id }};
					});
				}
				else{
					swal("Cancelled", "Task is not submmitted", "error");
				}
			});
		});
		</script>
@endsection