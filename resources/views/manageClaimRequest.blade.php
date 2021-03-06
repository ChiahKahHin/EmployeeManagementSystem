@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Manage Claim Requests
@endsection

@section('pageTitle')
	Manage Claim Requests
@endsection

@section('content')
	<div class="card-box mb-30">
		<div class="pd-20">
			<h4 class="text-blue h4 pb-2">All Claim Requests
				<a href="{{ route('applyBenefitClaim') }}" style="float: right" class="btn btn-outline-primary">
                    <i class="icon-copy dw dw-add"></i> Apply Benefit Claim
                </a>
			</h4>
			<h6 class="text-blue h6">Claim Requests Status</h6>
			@php
				if(Auth::user()->isAccess('admin', 'hrmanager', 'manager')){
					$statuses = array("To be approve", "Waiting Approval", "Rejected", "Approved", "Cancelled");
				}
				else{
					$statuses = array("Waiting Approval", "Rejected", "Approved", "Cancelled");
				}
			@endphp
			<select class="w-25 selectpicker" id="status" onchange="changeStatus();">
				<option value="">All Claim Status</option>
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
						<th>Claim Type</th>
						<th>Claim Amount</th>
						<th>Claim Date</th>
						@if (!Auth::user()->isEmployee())
							<th>Claim Employee</th>
						@endif
						<th>Claim Status</th>
						<th class="datatable-nosort">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($claimRequests as $claimRequest)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td class="table-plus">{{ ucwords($claimRequest->getClaimType->claimType) }}</td>
						<td>RM {{ $claimRequest->claimAmount }}</td>
						<td>{{ date("d F Y", strtotime($claimRequest->claimDate)) }} </td>
						@if (!Auth::user()->isEmployee())
							<td>{{ $claimRequest->getEmployee->getFullName() }} </td>
						@endif
						<td>{!! $claimRequest->getStatus() !!}</td>
						<td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									<a class="dropdown-item" href="{{ route('viewClaimRequest', ['id' =>$claimRequest->id]) }}"><i class="dw dw-eye"></i> View</a>
									@if (Auth::user()->isAdmin())
										{{-- <a class="dropdown-item" href="{{ route('editBenefitClaim', ['id' => $claimRequest->id]) }}"><i class="dw dw-edit2"></i> Edit</a> --}}
										<a class="dropdown-item deleteClaimRequest" id="{{ $claimRequest->id }}" value="{{ ucwords($claimRequest->getClaimType->claimType) }}" data-amount="{{ $claimRequest->claimAmount }}"><i class="dw dw-delete-3"></i> Delete</a>
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

	$(document).on('click', '.deleteClaimRequest', function() {
		var claimId = $(this).attr('id');
		var claimType = $(this).attr('value');
		var claimAmount = $(this).data('amount');
		swal({
			title: 'Delete this claim request?',
			html: 'Claim type: ' + claimType + '<br>' +
				  'Claim amount: RM' + claimAmount,
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: "btn btn-danger",
			confirmButtonText: "Yes, delete it!"
		}).then((result) => {
			if (result.value){
				swal({
					title: "Deleted!",
					text: claimType + " claim request removed from system",
					type: "success",
					showCancelButton: false,
					//timer:3000
				}).then(function(){
					window.location.href = "/deleteClaimRequest/" + claimId;
				});
			}
			else{
				swal("Cancelled", "Claim request is not removed from system", "error");
			}
		});
	});
</script>

@endsection
