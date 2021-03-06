@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Add Task
@endsection

@section('pageTitle')
	Add Task
@endsection

@section('content')
	@if (count($personInCharges) == 0)
		@if (Auth::user()->isAccess('manager', 'employee'))
			<script>
				swal({
					title: 'Warning',
					html: 'There is no person in charge added at the moment<br> Please inform the Human Resource Department',
					type: 'warning',
					confirmButtonClass: 'btn btn-danger',
				}).then(function(){
					window.location.href = "/";
				});
			</script>
		@else
			<script>
				swal({
					title: 'Warning',
					html: 'There is no person in charge added at the moment !',
					type: 'warning',
					confirmButtonClass: 'btn btn-danger',
				}).then(function(){
					window.location.href = "/";
				});
			</script>
		@endif
	@endif
	<div class="pd-20 card-box mb-30">
		<div class="clearfix">
			<div class="pull-left mb-10">
				<h4 class="text-blue h4">Add Task</h4>
			</div>
		</div>
		<form action="{{ route('addTask') }}" method="POST">
			@csrf
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Task Title</label>
						<input class="form-control @error('title') form-control-danger @enderror" type="text" name="title" placeholder="Enter task title" value="{{ old('title') }}" required>
						
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
						<input class="form-control @error('description') form-control-danger @enderror" type="text" name="description" placeholder="Enter task description" value="{{ old('description') }}" required>
						
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
						<label style="font-size: 13px;"><i>(Multiple Person In Charge?)</i>&nbsp; &nbsp;<input class="form-control switch-btn" type="checkbox" id="multiplePICCheckbox" name="multiplePICCheckbox" onchange="multiplePersonInCharge();" data-size="small" data-color="#0099ff" {{ (old('multiplePICCheckbox')? "checked": null) }}></label>
						<select class="form-control custom-select2 @error('personInCharge') form-control-danger @enderror" id="personInCharge" name="personInCharge" multiple="multiple" required>
							@foreach ($personInCharges as $personInCharge)
								<option value="{{ $personInCharge->id }}" {{ (old('personInCharge') == $personInCharge->id ? "selected": null) }}>{{ $personInCharge->getFullName() }}</option>
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
								<option value="{{ $priority }}" {{ (old('priority') == $priority ? "selected": null) }}>{{ ucfirst($priority) }}</option>
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
						<input class="form-control @error('dueDate') form-control-danger @enderror" type="date" min="@php echo date("Y-m-d", strtotime("+1 day")) @endphp" name="dueDate" placeholder="Select task due date" value="{{ old('dueDate') }}" required>
						
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
					<button type="submit" class="btn btn-primary btn-block">Add Task</button>
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
				//timer:5000
			});
		</script>
	@endif
	@if (session('error'))
		<script>
			swal({
				title: '{{ session("error") }}',
				html: '{{ session("error1") }}',
				type: 'error',
				confirmButtonClass: 'btn btn-success',
				//timer:7500
			});
		</script>
	@endif
@endsection

@section("script")
	<script>
		$(document).ready(function() {
			$('.custom-select2').select2({
				placeholder : "Select Person In Charge",
				allowClear: true
			});
			multiplePersonInCharge();
		});

		function multiplePersonInCharge(){
			var checked = document.getElementById('multiplePICCheckbox').checked;
			var personInCharge = document.getElementById('personInCharge');
			if(checked ==  true){
				personInCharge.setAttribute('multiple', 'multiple');
				personInCharge.removeAttribute('name');
				personInCharge.setAttribute('name', 'personInCharge[]');
			}
			else{
				personInCharge.removeAttribute('multiple');
				personInCharge.removeAttribute('name');
				personInCharge.setAttribute('name', 'personInCharge');
				$(".custom-select2").val(null).trigger('change');
			}
		}
	</script>
@endsection
