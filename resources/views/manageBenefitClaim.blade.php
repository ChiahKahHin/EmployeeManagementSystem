@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Manage Benefit Claims
@endsection

@section('pageTitle')
	Manage Benefit Claims
@endsection

@section('content')
	<div class="card-box mb-30">
		<div class="pd-20">
			<h4 class="text-blue h4">All Benefit Claims</h4>
		</div>
		<div class="pb-20">
			<table class="data-table table stripe hover nowrap">
				<thead>
					<tr>
						<th width="5%">#</th>
						<th>Claim Type</th>
						<th>Claim Amount</th>
						<th class="datatable-nosort" width="15%">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($benefitClaims as $benefitClaim)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td class="table-plus">{{ ucwords($benefitClaim->claimType) }}</td>
						<td>RM {{ $benefitClaim->claimAmount }}</td>
						<td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									{{-- <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a> --}}
									<a class="dropdown-item" href="{{ route('editBenefitClaim', ['id' => $benefitClaim->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
									<a class="dropdown-item deleteBenefitClaim" id="{{ $benefitClaim->id }}" value="{{ ucwords($benefitClaim->claimType) }}"><i class="dw dw-delete-3"></i> Delete</a>
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
	$(document).on('click', '.deleteBenefitClaim', function() {
		var claimId = $(this).attr('id');
		var claimType = $(this).attr('value');
		swal({
			title: 'Delete this benefit claim?',
			text: 'Claim type: ' + claimType,
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: "btn btn-danger",
			confirmButtonText: "Yes, delete it!"
		}).then((result) => {
			if (result.value){
				swal({
					title: "Deleted!",
					text: claimType + " benefit claim removed from system",
					type: "success",
					showCancelButton: false,
					timer: 3000
				}).then(function(){
					window.location.href = "/deleteBenefitClaim/" + claimId;
				});
			}
			else{
				swal("Cancelled", "Benefit claim is not removed from system", "error");
			}
		});
	});
</script>

@endsection