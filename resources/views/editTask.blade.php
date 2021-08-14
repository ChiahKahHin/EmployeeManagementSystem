@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Edit Task
@endsection

@section('pageTitle')
	Edit Task
@endsection

@section('content')
	<div class="pd-20 card-box mb-30">
		<div class="clearfix">
			<div class="pull-left mb-10">
				<h4 class="text-blue h4">Edit Task</h4>
			</div>
		</div>
		<form action="{{ route('editTask', ['id' => $task->id]) }}" method="POST">
			@csrf
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Task Title</label>
						<input class="form-control @error('title') form-control-danger @enderror" type="text" name="title" placeholder="Enter task title" value="{{ old('title', $task->title) }}" required>
						
						@error("title")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Task Description</label>
						<input class="form-control @error('description') form-control-danger @enderror" type="text" name="description" placeholder="Enter task description" value="{{ old('description', $task->description) }}" required>
						
						@error("description")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Person In Charge</label>

						<select class="form-control selectpicker @error('personInCharge') form-control-danger @enderror" name="personInCharge" required>
							<option value="" selected disabled hidden>Select Person In Charge</option>
							@foreach ($personInCharges as $personInCharge)
								<option value="{{ $personInCharge->id }}" {{ (old('personInCharge', $task->personInCharge) == $personInCharge->id ? "selected": null) }}>{{ ucfirst($personInCharge->firstname) }} {{ ucfirst($personInCharge->lastname ) }}</option>
							@endforeach
						</select>

						@error("personInCharge")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Task Priority</label>
						@php
							$priorities = array("High", "Medium", "Low");
						@endphp
						<select class="form-control selectpicker @error('priority') form-control-danger @enderror" name="priority" required>
							<option value="" selected disabled hidden>Select task priority</option>
							@foreach ($priorities as $priority)
								<option value="{{ $priority }}" {{ (old('priority', $task->priority) == $priority ? "selected": null) }}>{{ ucfirst($priority) }}</option>
							@endforeach
						</select>
						
						@error("priority")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror	
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Task Due Date</label>
						<input class="form-control @error('dueDate') form-control-danger @enderror" type="date" min="@php echo date("Y-m-d", strtotime("+1 day")) @endphp" name="dueDate" placeholder="Select task due date" value="{{ old('dueDate', $task->dueDate) }}" required>
						
						@error("dueDate")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Edit Task</button>
				</div>
			</div>
		</form>
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
