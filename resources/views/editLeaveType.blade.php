@extends("layouts.template")

@section('title')
    {{ Auth::user()->getRoleName() }} | Edit Leave Type
@endsection

@section('pageTitle')
    Edit Leave Type
@endsection

@section('content')
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left mb-10">
                <h4 class="text-blue h4">Edit Leave Type</h4>
            </div>
        </div>

        <form action="{{ route('editLeaveType', ['id' => $leaveType->id]) }}" method="POST">
			@csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Leave Type <i>(must be unique)</i></label>
                        <input class="form-control @error('leaveType') form-control-danger @enderror" type="text" name="leaveType" placeholder="Enter leave type" value="{{ old('leaveType', $leaveType->leaveType) }}" required>
						
						@error("leaveType")
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
                        <label>Leave Limit <i>(per annum)</i></label>
                        <input class="form-control @error('leaveLimit') form-control-danger @enderror" type="number" min="1" step="1" name="leaveLimit" placeholder="Enter leave limit" value="{{ old('leaveLimit', $leaveType->leaveLimit) }}" required>
						
						@error("leaveLimit")
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
						<label>Gender</label>
						@php
							$genders = array("Male", "Female");
						@endphp
						<select class="form-control selectpicker @error('gender') form-control-danger @enderror" id="gender" name="gender" onchange="checkGender();" required>
							<option value="All" {{ (old('gender', $leaveType->gender) == "All" ? "selected": null) }}>Both genders</option>
							@foreach ($genders as $gender)
								<option value="{{ $gender }}" {{ (old('gender', $leaveType->gender) == $gender ? "selected": null) }}>{{ $gender }}</option>
							@endforeach
						</select>
						
						@error("gender")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror	
                    </div>
                </div>
            </div>

			<div class="form-group" id="showMarriedOption" style="display: none;">
				<div class="row">
					<div class="col-md-6">
						<label>Only for married employee?</label>
						<input class="form-control switch-btn" type="checkbox" name="maritalStatus" data-size="small" data-color="#0099ff" value="1" @if(($errors->isNotEmpty() && old('maritalStatus')) || ($errors->isEmpty() && $leaveType->maritalStatus == 1)) checked @endif>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Edit Leave Type</button>
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
@endsection

@section('script')
	<script>
		$(document).ready(function() {
			var gender = document.getElementById('gender').value;

			if(gender != ""){
				checkGender();
			}
		});

		function checkGender() {
			var gender = document.getElementById('gender').value;
			if(gender != "All"){
				document.getElementById('showMarriedOption').removeAttribute('style');
			}
			else{
				document.getElementById('showMarriedOption').setAttribute('style', 'display:none;');
			}
		}
	</script>
@endsection