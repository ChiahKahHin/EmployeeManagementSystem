@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Manage Claim Types
@endsection

@section('pageTitle')
	Manage Claim Types
@endsection

@section('content')
	<div class="card-box mb-30">
		<div class="pd-20">
			<h4 class="text-blue h4">All Claim Types</h4>
		</div>
		<div class="pb-20">
			<table class="data-table table stripe hover nowrap">
				<thead>
					<tr>
						<th width="5%">#</th>
						<th>Claim Category</th>
						<th>Claim Type</th>
						<th>Claim Amount</th>
						<th>Claim Period</th>
						<th class="datatable-nosort" width="15%">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($claimTypes as $claimType)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td class="table-plus">{{ $claimType->getClaimCategory->claimCategory }}</td>
						<td>{{ ucwords($claimType->claimType) }}</td>
						<td>RM {{ $claimType->claimAmount }}</td>
						<td>{{ $claimType->claimPeriod }}</td>
						<td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									{{-- <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a> --}}
									<a class="dropdown-item" href="{{ route('editClaimType', ['id' => $claimType->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
									<a class="dropdown-item deleteClaimType" id="{{ $claimType->id }}" value="{{ ucwords($claimType->claimType) }}"><i class="dw dw-delete-3"></i> Delete</a>
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
	$(document).on('click', '.deleteClaimType', function() {
		var claimId = $(this).attr('id');
		var claimType = $(this).attr('value');
		swal({
			title: 'Delete this claim type?',
			text: 'Claim type: ' + claimType,
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: "btn btn-danger",
			confirmButtonText: "Yes, delete it!"
		}).then((result) => {
			if (result.value){
				swal({
					title: "Deleted!",
					text: claimType + " claim type removed from system",
					type: "success",
					showCancelButton: false,
					timer: 3000
				}).then(function(){
					window.location.href = "/deleteClaimType/" + claimId;
				});
			}
			else{
				swal("Cancelled", "Claim type is not removed from system", "error");
			}
		});
	});
</script>

@endsection
