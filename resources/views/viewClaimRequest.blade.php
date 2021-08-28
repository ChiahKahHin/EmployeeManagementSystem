@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | View Claim Request Details
@endsection

@section('pageTitle')
	View Claim Request Details
@endsection

@section('content')
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
					<tr>
						<td class="font-weight-bold">Claim Employee</td>
						<td>{{ $claimRequest->getEmployee->getFullName() }}</td>
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
					<td>{{ $claimRequest->getStatus() }}</td>
				</tr>
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
		@if (Auth::user()->isAdmin() || Auth::user()->isHrManager() || ($claimRequest->claimManager == Auth::user()->id))
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