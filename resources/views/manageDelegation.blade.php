@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Manage Delegation
@endsection

@section('pageTitle')
	Manage Delegation
@endsection

@section('content')
	<div class="card-box mb-30">
		<div class="pd-20">
			<h4 class="text-blue h4">All Delegation
                <a href="{{ route('addDelegation') }}" style="float: right" class="btn btn-outline-primary">
                    <i class="icon-copy dw dw-add"></i> Add Delegation
                </a>
            </h4>
		</div>
		<div class="pb-20">
			<table class="data-table table stripe hover nowrap">
				<thead>
					<tr>
						<th>#</th>
						<th>Delegate Manager</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Status</th>
						<th class="datatable-nosort">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($delegations as $delegation)
						
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td class="table-plus">{{ $delegation->getDelegateManager->getFullName() }}</td>
						<td>{{ date("d F Y", strtotime($delegation->startDate)) }} </td>
						<td>{{ date("d F Y", strtotime($delegation->endDate)) }} </td>
						<td>{{ $delegation->getStatus() }}</td>
						<td>
							<div class="dropdown">

								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									<a class="dropdown-item" href="{{ route('viewDelegation', ['id' => $delegation->id]) }}"><i class="dw dw-eye"></i> View</a>
									@if ($delegation->status == 0 || $delegation->status == 1)
										<a class="dropdown-item cancelDelegation" id="{{ $delegation->id }}" value="{{ $delegation->getDelegateManager->getFullName() }}"><i class="dw dw-cancel"></i> Cancel</a>
									@else
										<a class="dropdown-item deleteDelegation" id="{{ $delegation->id }}" value="{{ $delegation->getDelegateManager->getFullName() }}"><i class="dw dw-delete-3"></i> Delete</a>
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
@endsection

@section('script')
    <script>
        $(document).on('click', '.cancelDelegation', function() {
            var delegationID = $(this).attr('id');
            var delegateManager = $(this).attr('value');
            swal({
                title: 'Cancel this delegation?',
                text: 'Delegate Manager: ' + delegateManager,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: "Yes, cancel it!"
            }).then((result) => {
                if (result.value){
                    swal({
                        title: "Cancelled!",
                        text: "This approval delegation was cancelled",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/cancelDelegation/" + delegationID;
                    });
                }
                else{
                    swal("Cancelled", "This approval delegation is not cancelled", "error");
                }
            });
        });
		
        $(document).on('click', '.deleteDelegation', function() {
            var delegationID = $(this).attr('id');
            var delegateManager = $(this).attr('value');
            swal({
                title: 'Delete this delegation?',
                text: 'Delegate Manager: ' + delegateManager,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value){
                    swal({
                        title: "Deleted!",
                        text: "Delegation removed from system",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/deleteDelegation/" + delegationID;
                    });
                }
                else{
                    swal("Cancelled", "Delegation is not removed from system", "error");
                }
            });
        });
    </script>
@endsection