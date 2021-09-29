@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | View Carried Forward Leave Details
@endsection

@section('pageTitle')
	View Carried Forward Leave Details
@endsection

@section('content')
	@if ($carriedForwardLeave->managerID == null)
		<div class="card">
			<div class="row d-flex justify-content-between top-progressbar2">
				<div class="d-flex pb-4">
					<h5>Carried Forward Leave Status</h5>
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<div class="col-12">
					<ul id="progressbar2" class="text-center">
						<li class="@if($carriedForwardLeave->status >= 0) active @endif step0" @if($carriedForwardLeave->status >= 0) style="cursor: pointer;" data-toggle="modal" data-target="#apply-modal" @endif></li>
						<li class="@if($carriedForwardLeave->status >= 2) active @endif step0" @if($carriedForwardLeave->status >= 2) style="cursor: pointer;" data-toggle="modal" data-target="#completed-modal" @endif></li>
					</ul>
				</div>
			</div>

			<div class="row justify-content-between top-progressbar2">
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Applied</p>
					</div>
				</div>
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Completed<br></p>
					</div>
				</div>
			</div>
		</div>
	@else		
		<div class="card">
			<div class="row d-flex justify-content-between top-progressbar3">
				<div class="d-flex pb-4">
					<h5>Carried Forward Leave Status</h5>
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<div class="col-12">
					<ul id="progressbar3" class="text-center">
						<li class="@if($carriedForwardLeave->status >= 0) active @endif step0" @if($carriedForwardLeave->status >= 0) style="cursor: pointer;" data-toggle="modal" data-target="#waiting-approval-modal" @endif></li>
						<li class="@if($carriedForwardLeave->status >= 1) active @endif step0" @if($carriedForwardLeave->status >= 1) style="cursor: pointer;" data-toggle="modal" data-target="#approved-rejected-modal" @endif></li>
						<li class="@if($carriedForwardLeave->status >= 1) active @endif step0" @if($carriedForwardLeave->status >= 1) style="cursor: pointer;" data-toggle="modal" data-target="#completed-modal" @endif></li>
					</ul>
				</div>
			</div>

			<div class="row justify-content-between top-progressbar3">
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Waiting <br> Approval</p>
					</div>
				</div>
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">@if($carriedForwardLeave->status < 1) Approved/Rejected @elseif($carriedForwardLeave->status == 1) Rejected @elseif($carriedForwardLeave->status == 2) Approved @endif</p>
					</div>
				</div>
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Completed<br></p>
					</div>
				</div>
			</div>
		</div>
	@endif

	{{-- Carried Forward Leave Modals --}}
	<div class="modal fade" id="apply-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Carried Forward Leave Applied</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						The carried forward leave is applied.
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
					<h4 class="modal-title" id="myLargeModalLabel">Carried Forward Leave Waiting Approval</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This carried forward leave is waiting for the manager approval. <br>
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
						@if ($carriedForwardLeave->status == 1)
							Carried Forward Leave Rejected
						@elseif ($carriedForwardLeave->status == 2)
							Carried Forward Leave Approved
						@endif
					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						@if ($carriedForwardLeave->status == 1)
							This carried forward leave is rejected by the manager.
						@elseif ($carriedForwardLeave->status == 2)
							This carried forward leave is approved by the manager.
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
					<h4 class="modal-title" id="myLargeModalLabel">Carried Forward Leave Application Completed</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This carried forward leave application is completed. <br>
						@if ($carriedForwardLeave->managerID == null)
							No Approval is needed.
						@endif
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	{{-- Carried Forward Leave Details Tables --}}
	<div class="pd-20 card-box mb-30">
		<div class="clearfix mb-20">
			<div class="pull-left">
				<h4 class="text-blue h4">Carried Forward Leave Details</h4>
			</div>
		</div>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th scope="col" width="30%">Carried Forward Leave Details</th>
					<th scope="col" width="70%">Carried Forward Leave Information</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="font-weight-bold">Entitled Carried Forward Leave</td>
					<td>{{ $carriedForwardLeave->leaveLimit }} days</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Employee</td>
					<td>{{ $carriedForwardLeave->getEmployee->getFullName() }}</td>
				</tr>
				@if ($carriedForwardLeave->managerID != null)
					<tr>
						<td class="font-weight-bold">Manager</td>
						<td>{{ $carriedForwardLeave->getManager->getFullName() }}</td>
					</tr>
				@endif
				@if ($carriedForwardLeave->delegateManagerID != null)
					<tr>
						<td class="font-weight-bold">Delegate Manager</td>
						<td>{{ $carriedForwardLeave->getDelegateManager->getFullName() }}</td>
					</tr>
				@endif
				<tr>
					<td class="font-weight-bold">Status</td>
					<td>{!! $carriedForwardLeave->getStatus() !!}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Carried Forward Leave Expired After</td>
					<td>{{ date("d F Y", strtotime($carriedForwardLeave->useBefore)) }}</td>
				</tr>
				@if ($carriedForwardLeave->rejectedReason != null)
					<tr>
						<td class="font-weight-bold">Rejected Reason</td>
						<td>{{ $carriedForwardLeave->rejectedReason }}</td>
					</tr>
				@endif
				<tr>
					<td class="font-weight-bold">Carried Forward Leave Created Date & Time</td>
					<td>{{ date("d F Y, g:ia", strtotime($carriedForwardLeave->created_at)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Carried Forward Leave Updated Date & Time</td>
					<td>{{ date("d F Y, g:ia", strtotime($carriedForwardLeave->updated_at)) }}</td>
				</tr>
			</tbody>
		</table>
		@if (!Auth::user()->isEmployee())
			@if ($carriedForwardLeave->status == 0 && (Auth::user()->isAdmin() || $carriedForwardLeave->managerID == Auth::id() || $carriedForwardLeave->delegateManagerID == Auth::id()))
				<div class="row">
					<div class="col-md-6">
						<button type="button" id="approveCFRequest" class="btn btn-primary btn-block">Approve C/F Leave Application</button>
					</div>
					<div class="col-md-6">
						<button type="button" data-toggle="modal" data-target="#login-modal" class="btn btn-primary btn-block">Reject C/F Leave Application</button>
					</div>
				</div>
			@endif
		@endif
		
		<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Reject C/F Leave Application?</h2>
						</div>
						<div id="reasonTextInput" class="input-group custom">
							<input type="text" id="reasonOfRejectingRequest" class="form-control form-control-lg" placeholder="Reason of rejecting the C/F leave application">
						</div>
						<div id="reasonErrorMessage"></div>
						<div class="row">
							<div class="col-sm-12">
								<div class="input-group mb-0">
									<button type="button" id="rejectCFRequest" class="btn btn-primary btn-lg btn-block">Reject C/F Application</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@if (session('message'))
		<script>
			swal({
				title: '{{ session("message") }}',
				html: '{{ session("message1") }}',
				type: 'success',
				confirmButtonClass: 'btn btn-success',
				//timer:5000
			});
		</script>
	@endif
@endsection

@section("script")
	<script>
		$('#approveCFRequest').on('click', function(){
			swal({
				title: "Approve this C/F leave application?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonClass: "btn btn-success",
				confirmButtonText: "Yes, approve it!"
			}).then((result) => {
				if(result.value){
					swal({
                        title: "Approved!",
                        text: "C/F leave application approved",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/approveCFRequest/" + {{ $carriedForwardLeave->id }};
                    });
				}
				else{
					swal("Cancelled", "Carried forward leave application is not approved", "error");
				}
			});
		});
		
		var reason = document.getElementById('reasonOfRejectingRequest');
		reason.addEventListener("keyup", function(event){
			if(event.keyCode === 13){
				document.getElementById('rejectCFRequest').click();
			}
		});
		
		$('#rejectCFRequest').on('click', function(){
			if(reason.value != ""){
				document.getElementById('reasonTextInput').setAttribute("style", "margin-bottom: 25px");
				
				var reasonErrorMessage = document.getElementById('reasonErrorMessage');
				reasonErrorMessage.setAttribute("class", "");
				reasonErrorMessage.innerHTML = "";
				
				swal({
					title: "Reject this C/F leave application?",
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
							text: "C/F leave application rejected",
							type: "success",
							showCancelButton: false,
							//timer:3000
						}).then(function(){
							window.location.href = "/rejectCFRequest/" + {{ $carriedForwardLeave->id }} + '/' + escape(reason.value);
						});
					}
					else{
						swal("Cancelled", "Carried forward leave application is not rejected", "error");
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