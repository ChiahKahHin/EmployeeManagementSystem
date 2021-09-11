@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Manage Tasks
@endsection

@section('pageTitle')
	Manage Tasks
@endsection

@section('content')
	<div class="card-box mb-30">
		<div class="pd-20">
			<h4 class="text-blue h4">All Tasks</h4>
		</div>
		<div class="pb-20">
			<table class="data-table table stripe hover nowrap">
				<thead>
					<tr>
						<th>#</th>
						<th>Title</th>
						@if (Auth::user()->isEmployee())
							<th>Manager</th>
						@else
							<th>Person In Charge</th>
						@endif
						<th>Priority</th>
						<th>Due Date</th>
						<th>Status</th>
						<th class="datatable-nosort">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($tasks as $task)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td class="table-plus">{{ $task->title }}</td>
						@if (Auth::user()->isEmployee())
							<td>{{ $task->getManager->getFullName()}}</td>
							
						@else
							<td>{{ $task->getPersonInCharge->getFullName() }}</td>
						@endif
						<td>{{ $task->priority }}</td>
						<td>{{ date("d F Y", strtotime($task->dueDate)) }} </td>
						<td>{!! $task->getStatus() !!}</td>
						<td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									<a class="dropdown-item" href="{{ route('viewTask', ['id' => $task->id]) }}"><i class="dw dw-eye"></i> View</a>
									@if (Auth::user()->isAccess('admin', 'manager'))
										@if ($task->status == 0)
										<a class="dropdown-item" href="{{ route('editTask', ['id' => $task->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
										@endif
									@endif
									@if (Auth::user()->isAdmin())
										<a class="dropdown-item deleteTask" id="{{ $task->id }}" value="{{ $task->title }}"><i class="dw dw-delete-3"></i> Delete</a>
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
        $(document).on('click', '.deleteTask', function() {
            var taskID = $(this).attr('id');
            var taskTitle = $(this).attr('value');
            swal({
                title: 'Delete this task?',
                text: 'Task title: ' + taskTitle,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value){
                    swal({
                        title: "Deleted!",
                        text: "Task removed from system",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/deleteTask/" + taskID;
                    });
                }
                else{
                    swal("Cancelled", "Task is not removed from system", "error");
                }
            });
        });
    </script>
    
@endsection