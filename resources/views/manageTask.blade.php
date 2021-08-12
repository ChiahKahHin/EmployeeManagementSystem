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
						<th>Description</th>
						<th>Person In Charge</th>
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
						<td>{{ $task->description }}</td>
						<td>{{ $task->getPersonInCharge->firstname }} {{ $task->getPersonInCharge->lastname }}</td>
						<td>{{ $task->priority }}</td>
						<td>{{ date("d F Y", strtotime($task->dueDate)) }} </td>
						<td>{{ $task->getStatus() }}</td>
						<td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									<a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
									<a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
									<a class="dropdown-item deleteAdmin" id="{{ $task->id }}" value="{{ $task->username }}"><i class="dw dw-delete-3"></i> Delete</a>
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
