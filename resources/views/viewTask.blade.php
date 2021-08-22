@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | View Task Details
@endsection

@section('pageTitle')
	View Task Details
@endsection

@section('content')
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
				@if (Auth::user()->isEmployee())
					<tr>
						<td class="font-weight-bold">Manager</td>
						<td>{{ $task->getManager->getFullName() }}</td>
					</tr>
				@elseif (Auth::user()->isHrManager() || Auth::user()->isManager())
					<tr>
						<td class="font-weight-bold">Person In Charge</td>
						<td>{{ $task->getPersonInCharge->getFullName() }}</td>
					</tr>
				@else
					<tr>
						<td class="font-weight-bold">Manager</td>
						<td>{{ $task->getManager->getFullName() }}</td>
					</tr>
					<tr>
						<td class="font-weight-bold">Person In Charge</td>
						<td>{{ $task->getPersonInCharge->getFullName() }}</td>
					</tr>
				@endif
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
@endsection

@section("script")
	<script>
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
                        timer: 3000
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
							timer: 3000
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
						timer: 3000
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