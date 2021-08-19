@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Add Training Program
@endsection

@section('pageTitle')
	Add Training Program
@endsection

@section('content')
	<div class="pd-20 card-box mb-30">
		<div class="clearfix">
			<div class="pull-left mb-10">
				<h4 class="text-blue h4">Add Training Program</h4>
			</div>
		</div>

		<form action="{{ route('addTrainingProgram') }}" method="POST" enctype="multipart/form-data">
			@csrf
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Training Program Name</label>
						<input class="form-control @error('name') form-control-danger @enderror" type="text" name="name" placeholder="Enter training program name" value="{{ old('name') }}" required>
						
						@error("name")
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
						<label>Training Program Description</label>
						<textarea class="form-control @error('description') form-control-danger @enderror" id="description" name="description" style="min-height:100px; max-height:200px; height:45px; resize: vertical;" placeholder="Enter training program description" maxlength="255" onkeyup="countWords()" data-gramm_editor="false" required>{{ old('description') }}</textarea>
						
						<div id="description_word_count" class="text-sm" style="text-align: right"></div>
						
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
						<label>Training Program Venue</label>
						<input class="form-control @error('venue') form-control-danger @enderror" type="text" name="venue" placeholder="Enter training program venue" value="{{ old('venue') }}" required>
						
						@error("venue")
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
						<label>Training Program Date & Time</label>
						<input class="form-control @error('dateAndTime') form-control-danger @enderror" type="datetime-local" min="@php echo date("Y-m-d\TH:i", strtotime("tomorrow")) @endphp" id="dateAndTime" name="dateAndTime" placeholder="Select program date & time" value="{{ old('dateAndTime') }}" required>
						
						@error("dateAndTime")
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
						<label>Training Program Poster</label>							
						<input type="file" class="form-control-file form-control height-auto @error('poster') form-control-danger @enderror" id="poster" name="poster" accept=".pdf,.jpg,.png,.jpeg" required>
						<label style="font-size: 14px;"><i>(Only attachments with .pdf, .jpg, .png, .jpeg extension can be accepted)</i></label>
						
						@error("poster")
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
						<label>Training program for specific department?</label>
						<input class="form-control switch-btn" type="checkbox" id="specificDepartment" name="specificDepartment" onchange="showInput();" data-size="small" data-color="#0099ff" {{ (old('specificDepartment')? "checked": null) }}>
					</div>
				</div>
			</div>

			<div id="showDepartment" style="display: none;">
				<div class="row">
					<div class="col-md-6">
						<label>Department</label>
						
						<select class="form-control selectpicker @error('department') form-control-danger @enderror" id="department" name="department" required>
							<option value="" selected disabled hidden>Select department</option>
							@foreach ($departments as $department)
								<option value="{{ $department->id }}" {{ (old('department') == $department->id ? "selected": null) }}>{{ $department->departmentName }}</option>
							@endforeach
						</select>
						
						@error("department")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
				<br>
			</div>

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Add Training Program</button>
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

@section("script")
	<script>
		$(document).ready(function() {
			countWords();
			showInput();
		});

		function countWords(){
			var words = document.getElementById('description');
			$('#description_word_count').text(words.value.length + "/" + words.maxLength);
		};

		function showInput() {
			var checked = document.getElementById('specificDepartment').checked;
			if(checked == true){
				document.getElementById('showDepartment').removeAttribute('style');
				document.getElementById('department').setAttribute('required', '');
			}
			else{
				document.getElementById('showDepartment').setAttribute('style', 'display:none;');
				document.getElementById('department').removeAttribute('required');
			}
		}
	</script>
@endsection
