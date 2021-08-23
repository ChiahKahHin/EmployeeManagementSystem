@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Manage Memorandum
@endsection

@section('pageTitle')
	Manage Memorandum
@endsection

@section('content')
	<div class="card-box mb-30">
		<div class="pd-20">
			<h4 class="text-blue h4">All Memorandum</h4>
		</div>
		<div class="pb-20">
			<table class="data-table table stripe hover nowrap">
				<thead>
					<tr>
						<th>#</th>
						<th>Title</th>
						<th>Date</th>
						<th>Recipient</th>
						<th>Status</th>
						<th class="datatable-nosort">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($memos as $memo)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td class="table-plus">{{ $memo->memoTitle }}</td>
						<td>{{ date("d F Y", strtotime($memo->memoDate)) }} </td>
						@php
							$recipient = null;
							if($memo->memoRecipient == 0){
								$recipient = "All Employees";
							}
							else{
								$recipient = $memo->getDepartmentName() . " Department";
							}
						@endphp
						<td>{{ ucwords($recipient) }}</td>
						<td>{{ $memo->getStatus() }}</td>
						<td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									<a class="dropdown-item" href="{{ route('viewMemo', ['id' => $memo->id]) }}"><i class="dw dw-eye"></i> View</a>
									@if ($memo->memoStatus == 0)
										<a class="dropdown-item" href="{{ route('editMemo', ['id' => $memo->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
									@endif
									<a class="dropdown-item deleteMemo" id="{{ $memo->id }}" value="{{ $memo->memoTitle }}"><i class="dw dw-delete-3"></i> Delete</a>
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
				timer: 5000
			});
		</script>
	@endif
@endsection

@section('script')
    <script>
        $(document).on('click', '.deleteMemo', function() {
            var memoID = $(this).attr('id');
            var memoTitle = $(this).attr('value');
			const DELETE_URL = "{{ route('deleteMemo', ':id') }}";
			var url = DELETE_URL.replace(":id", memoID);

            swal({
                title: 'Delete this memo?',
                text: 'Memo title: ' + memoTitle,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value){
                    swal({
                        title: "Deleted!",
                        text: "Memo removed from system",
                        type: "success",
                        showCancelButton: false,
                        timer: 3000
                    }).then(function(){
                        window.location.href = url;
                    });
                }
                else{
                    swal("Cancelled", "Memo is not removed from system", "error");
                }
            });
        });
    </script>
@endsection