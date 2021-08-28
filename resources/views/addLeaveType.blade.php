@extends("layouts.template")

@section('title')
    {{ Auth::user()->getRoleName() }} | Add Leave Type
@endsection

@section('pageTitle')
    Add Leave Type
@endsection

@section('content')
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left mb-10">
                <h4 class="text-blue h4">Add Leave Type</h4>
            </div>
        </div>

        <form action="{{ route('addLeaveType') }}" method="POST">
			@csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Leave Type <i>(must be unique)</i></label>
                        <input class="form-control @error('leaveType') form-control-danger @enderror" type="text" name="leaveType" placeholder="Enter leave type" value="{{ old('leaveType') }}" required>
						
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
                        <input class="form-control @error('leaveLimit') form-control-danger @enderror" type="number" min="1" step="1" name="leaveLimit" placeholder="Enter leave limit" value="{{ old('leaveLimit') }}" required>
						
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
						<select class="form-control selectpicker @error('gender') form-control-danger @enderror" name="gender" required>
							<option value="" selected disabled hidden>Select gender</option>
							<option value="All" {{ (old('gender') == "All" ? "selected": null) }}>Both genders</option>
							@foreach ($genders as $gender)
								<option value="{{ $gender }}" {{ (old('gender') == $gender ? "selected": null) }}>{{ $gender }}</option>
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

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Add Leave Type</button>
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