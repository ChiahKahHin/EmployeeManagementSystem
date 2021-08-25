@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Manage Claim Categories
@endsection

@section('pageTitle')
	Manage Claim Categories
@endsection

@section('content')
	<div class="card-box mb-30">
		<div class="pd-20">
			<h4 class="text-blue h4">All Claim Categories</h4>
		</div>
		<div class="pb-20">
			<table class="data-table table stripe hover nowrap">
				<thead>
					<tr>
						<th width="15%" style="text-align: center;">#</th>
						<th style="text-align: center;">Claim Category</th>
						<th class="datatable-nosort" width="15%">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($claimCategories as $claimCategory)
					<tr>
						<td style="text-align: center;">{{ $loop->iteration }}</td>
						<td class="table-plus" style="text-align: center;">{{ ucwords($claimCategory->claimCategory) }}</td>
						<td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									<a class="dropdown-item" href="{{ route('editClaimCategory', ['id' => $claimCategory->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
									<a class="dropdown-item deleteClaimCategory" id="{{ $claimCategory->id }}" value="{{ ucwords($claimCategory->claimCategory) }}"><i class="dw dw-delete-3"></i> Delete</a>
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
	$(document).on('click', '.deleteClaimCategory', function() {
		var claimId = $(this).attr('id');
		var claimCategory = $(this).attr('value');
		swal({
			title: 'Delete this claim category?',
			text: 'Claim category: ' + claimCategory,
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: "btn btn-danger",
			confirmButtonText: "Yes, delete it!"
		}).then((result) => {
			if (result.value){
				swal({
					title: "Deleted!",
					text: claimCategory + " claim category removed from system",
					type: "success",
					showCancelButton: false,
					timer: 3000
				}).then(function(){
					window.location.href = "/deleteClaimCategory/" + claimId;
				});
			}
			else{
				swal("Cancelled", "Claim category is not removed from system", "error");
			}
		});
	});
</script>

@endsection
