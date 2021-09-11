@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | View Claim Request Details
@endsection

@section('pageTitle')
	View Claim Request Details
@endsection

@section('content')
	@if ($claimRequest->claimStatus <= 2)
		<div class="card">
			<div class="row d-flex justify-content-between top-progressbar3">
				<div class="d-flex pb-4">
					<h5>Claim Status</h5>
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<div class="col-12">
					<ul id="progressbar3" class="text-center">
						<li class="@if($claimRequest->claimStatus >= 0) active @endif step0" @if($claimRequest->claimStatus >= 0) style="cursor: pointer;" data-toggle="modal" data-target="#waiting-approval-modal" @endif></li>
						<li class="@if($claimRequest->claimStatus >= 1) active @endif step0" @if($claimRequest->claimStatus >= 1) style="cursor: pointer;" data-toggle="modal" data-target="#approved-rejected-modal" @endif></li>
						<li class="@if($claimRequest->claimStatus >= 1) active @endif step0" @if($claimRequest->claimStatus >= 1) style="cursor: pointer;" data-toggle="modal" data-target="#completed-modal" @endif></li>
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
						<p class="font-weight-bold">@if($claimRequest->claimStatus < 1) Approved/Rejected @elseif($claimRequest->claimStatus == 1) Rejected @elseif($claimRequest->claimStatus == 2) Approved @endif</p>
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
			<div class="row d-flex justify-content-between top-progressbar2">
				<div class="d-flex pb-4">
					<h5>Claim Status</h5>
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<div class="col-12">
					<ul id="progressbar2" class="text-center">
						<li class="@if($claimRequest->claimStatus >= 0) active @endif step0" @if($claimRequest->claimStatus >= 0) style="cursor: pointer;" data-toggle="modal" data-target="#waiting-approval-modal" @endif></li>
						<li class="@if($claimRequest->claimStatus >= 3) active @endif step0" @if($claimRequest->claimStatus >= 3) style="cursor: pointer;" data-toggle="modal" data-target="#cancelled-modal" @endif></li>
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
	@endif

	{{-- Claim Request Modals --}}
	<div class="modal fade" id="waiting-approval-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Claim Waiting Approval</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This claim request is waiting for the manager approval. <br>
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
						@if ($claimRequest->claimStatus == 1)
							Claim Request Rejected
						@elseif ($claimRequest->claimStatus == 2)
							Claim Request Approved
						@endif
					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						@if ($claimRequest->claimStatus == 1)
							This claim request is rejected by the manager.
						@elseif ($claimRequest->claimStatus == 2)
							This claim request is approved by the manager.
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
					<h4 class="modal-title" id="myLargeModalLabel">Claim Request Completed</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						@if ($claimRequest->claimStatus == 1)
							This claim request is rejected. <br>
							Kindly refer to the reason given by the manager and request for a new benefit claim.
						@elseif ($claimRequest->claimStatus == 2)
							This claim request is approved & completed.
						@endif
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
					<h4 class="modal-title" id="myLargeModalLabel">Claim Request Cancelled</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This claim request is cancelled.
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="pd-20 card-box mb-30">
		<div class="clearfix mb-20">
			<div class="pull-left">
				<h4 class="text-blue h4">Claim Request Details</h4>
			</div>
		</div>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th scope="col" width="25%">Claim Request Details</th>
					<th scope="col" width="75%">Claim Request Information</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="font-weight-bold">Claim Type</td>
					<td>{{ ucwords($claimRequest->getClaimType->claimType) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Claim Amount</td>
					<td>RM {{ $claimRequest->claimAmount }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Claim Date</td>
					<td>{{ date("d F Y", strtotime($claimRequest->claimDate)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Claim Description</td>
					<td>{!! nl2br($claimRequest->claimDescription) !!}</td>
				</tr>
				@if (Auth::user()->isAdmin() || Auth::user()->isHrManager())
				@endif
				<tr>
					<td class="font-weight-bold">Claim Employee</td>
					<td>{{ $claimRequest->getEmployee->getFullName() }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Claim Manager</td>
					<td>{{ $claimRequest->getManager->getFullName() }}</td>
				</tr>
				@if ($claimRequest->claimDelegateManager != null)
					<tr>
						<td class="font-weight-bold">Claim Delegate Manager</td>
						<td>{{ $claimRequest->getDelegateManager->getFullName() }}</td>
					</tr>
				@endif
				<tr>
					<td class="font-weight-bold">Attachment</td>
					<td>
						<img id="img-modal" src="data:image/png;base64,{{ chunk_split(base64_encode($claimRequest->claimAttachment)) }}" alt="{{ $claimRequest->getClaimType->claimType }}">
						<div id="image-modal" class="img-modal">
							<span class="close">&times;</span>
							<img class="img-modal-content" id="img-block-preview">
							<div id="caption"></div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Claim Status</td>
					<td>{!! $claimRequest->getStatus() !!}</td>
				</tr>
				@if ($claimRequest->claimRejectedReason != null)
					<tr>
						<td class="font-weight-bold">Claim Rejected Reason</td>
						<td>{{ $claimRequest->claimRejectedReason }}</td>
					</tr>
				@endif
				<tr>
					<td class="font-weight-bold">Claim Request Created Date & Time</td>
					<td>{{ date("d F Y, g:ia", strtotime($claimRequest->created_at)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Claim Request Updated Date & Time</td>
					<td>{{ date("d F Y, g:ia", strtotime($claimRequest->updated_at)) }}</td>
				</tr>
			</tbody>
		</table>
		@if (Auth::user()->isAdmin() || Auth::user()->isHrManager() || ($claimRequest->claimManager == Auth::user()->id) || ($claimRequest->claimDelegateManager == Auth::user()->id))
			@if ($claimRequest->claimStatus == 0)
				<div class="row">
					<div class="col-md-6">
						<button type="button" id="approveClaimRequest" class="btn btn-primary btn-block">Approve Claim Request</button>
					</div>
					<div class="col-md-6">
						<button type="button" data-toggle="modal" data-target="#login-modal" class="btn btn-primary btn-block">Reject Claim Request</button>
					</div>
				</div>
			@endif
		@endif

		@if ($claimRequest->claimStatus == 0 && $claimRequest->claimEmployee == Auth::id())
			<div class="row">
				<div class="col-md-12">
					<button type="button" id="cancelClaimRequest" class="btn btn-primary btn-block">Cancel Claim Request</button>
				</div>
			</div>
		@endif
		
		<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Reject Claim Request?</h2>
						</div>
						<div id="reasonTextInput" class="input-group custom">
							<input type="text" id="reasonOfRejectingClaimRequest" class="form-control form-control-lg" placeholder="Reason of rejecting the claim request">
						</div>
						<div id="reasonErrorMessage"></div>
						<div class="row">
							<div class="col-sm-12">
								<div class="input-group mb-0">
									<button type="button" id="rejectClaimRequest" class="btn btn-primary btn-lg btn-block">Reject Claim request</button>
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
	<script src="{{ asset('vendors/scripts/image-modal.js') }}"></script>
	<script>
	$('#cancelClaimRequest').on('click', function(){
			swal({
				title: "Cancel this claim request?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonClass: "btn btn-success",
				confirmButtonText: "Yes, cancel it!"
			}).then((result) => {
				if(result.value){
					swal({
                        title: "Cancelled!",
                        text: "Claim request cancelled",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/cancelClaimRequest/" + {{ $claimRequest->id }};
                    });
				}
				else{
					swal("Cancelled", "Claim request is not cancelled", "error");
				}
			});
		});

		$('#approveClaimRequest').on('click', function(){
			swal({
				title: "Approve this claim request?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonClass: "btn btn-success",
				confirmButtonText: "Yes, approve it!"
			}).then((result) => {
				if(result.value){
					swal({
                        title: "Approved!",
                        text: "Claim request approved",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/approveClaimRequest/" + {{ $claimRequest->id }};
                    });
				}
				else{
					swal("Cancelled", "Claim request is not approved", "error");
				}
			});
		});
		
		var reason = document.getElementById('reasonOfRejectingClaimRequest');
		reason.addEventListener("keyup", function(event){
			if(event.keyCode === 13){
				document.getElementById('rejectClaimRequest').click();
			}
		});
		
		$('#rejectClaimRequest').on('click', function(){
			if(reason.value != ""){
				document.getElementById('reasonTextInput').setAttribute("style", "margin-bottom: 25px");
				
				var reasonErrorMessage = document.getElementById('reasonErrorMessage');
				reasonErrorMessage.setAttribute("class", "");
				reasonErrorMessage.innerHTML = "";
				
				swal({
					title: "Reject this claim request?",
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
							text: "Claim request rejected",
							type: "success",
							showCancelButton: false,
							//timer:3000
						}).then(function(){
							window.location.href = "/rejectClaimRequest/" + {{ $claimRequest->id }} + '/' + reason.value;
						});
					}
					else{
						swal("Cancelled", "Claim request is not rejected", "error");
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