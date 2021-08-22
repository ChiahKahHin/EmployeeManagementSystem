@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Apply Leave
@endsection

@section('pageTitle')
	Apply Leave
@endsection

@section('content')
	<div class="pd-20 card-box mb-30">
		<div class="clearfix">
			<div class="pull-left mb-10">
				<h4 class="text-blue h4">Apply Leave</h4>
			</div>
		</div>
		<form action="{{ route('applyLeave') }}" method="POST">
			@csrf
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Leave Type</label>

						<select class="form-control selectpicker @error('leaveType') form-control-danger @enderror" id="leaveType" name="leaveType" onchange="checkLeaveLimit();" required>
							<option value="" selected disabled hidden>Select Leave Type</option>
							@foreach ($leaveTypes as $leaveType)
								<option value="{{ $leaveType->id }}" data-leaveID="{{ $leaveType->id }}" data-leaveType="{{ $leaveType->leaveType }}" data-leaveLimit="{{ $leaveType->leaveLimit }}" {{ (old('leaveType') == $leaveType->id ? "selected": null) }}>{{ ucfirst($leaveType->leaveType) }}</option>
							@endforeach
						</select>

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
						<label>Leave Start Date</label>
						<input class="form-control @error('leaveStartDate') form-control-danger @enderror" type="date" min="@php echo date("Y-m-d") @endphp" name="leaveStartDate" placeholder="Select leave start date" value="{{ old('leaveStartDate') }}" required>
						
						@error("leaveStartDate")
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
						<label id="leaveDurationLabel">Leave Duration</label>
						<input class="form-control @error('leaveDuration') form-control-danger @enderror" type="number" min="1" step="1" id="leaveDuration" name="leaveDuration" placeholder="Enter leave duration" onkeyup="checkLeaveLimit();" value="{{ old('leaveDuration') }}" required>
						
						@error("leaveDuration")
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
						<label>Leave Description</label>
						<input class="form-control @error('leaveDescription') form-control-danger @enderror" type="text" name="leaveDescription" placeholder="Enter leave description" value="{{ old('leaveDescription') }}" required>
						
						@error("leaveDescription")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Apply Leave</button>
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
	@if (session('error'))
		<script>
			swal({
				title: '{{ session("error") }}',
				html: '{{ session("error1") }}',
				type: 'error',
				confirmButtonClass: 'btn btn-success',
				timer: 5000
			});
		</script>
	@endif
@endsection

@section("script")
	<script>
		$(document).ready(function() {
			checkLeaveLimit();
		});

		function checkLeaveLimit(){
			var leaveTypeInput = document.getElementById('leaveType');

			if(leaveTypeInput.value != ""){
				var leaveID = leaveTypeInput.options[leaveTypeInput.selectedIndex].getAttribute('data-leaveID');
				var leaveType = leaveTypeInput.options[leaveTypeInput.selectedIndex].getAttribute('data-leaveType');
				var leaveLimit = leaveTypeInput.options[leaveTypeInput.selectedIndex].getAttribute('data-leaveLimit');
				
				var totalApprovedLeave = 0;
				@foreach ($approvedLeaves as $approvedLeave)
					var approvedLeaveType = {{ $approvedLeave->leaveType }};
					var approvedLeaveDuration = {{ $approvedLeave->leaveDuration }};
					var approvedLeaveDate = new Date(Date.parse("{{ date("d F Y", strtotime($approvedLeave->leaveStartDate)) }}"));
					var currentYear = new Date();

					if((approvedLeaveType == leaveID) && (approvedLeaveDate.getFullYear() == currentYear.getFullYear())){
						totalApprovedLeave += approvedLeaveDuration;
					}
				@endforeach

				var remainingLeave = leaveLimit - totalApprovedLeave;
				var leaveBalance = remainingLeave;

				document.getElementById('leaveDuration').setAttribute('max', remainingLeave);

				var leaveDurationInput = document.getElementById('leaveDuration').value;

				if(leaveDurationInput != ""){
					remainingLeave = remainingLeave - leaveDurationInput;
				}

				if(leaveDurationInput != "" && leaveDurationInput < 1){
					document.getElementById('leaveDurationLabel').innerHTML = "Leave Duration (Minimum is 1 day)";
					document.getElementById('leaveDurationLabel').removeAttribute('style');
				}
				else{
					if(leaveDurationInput == leaveBalance){
						document.getElementById('leaveDurationLabel').innerHTML = "Leave Duration (Maximum leave duration is reached)";
						document.getElementById('leaveDurationLabel').removeAttribute('style');
					}
					else if(remainingLeave >= 0){
						document.getElementById('leaveDurationLabel').innerHTML = "Leave Duration (Remaining Leave Duration: " + remainingLeave + " days)";
						document.getElementById('leaveDurationLabel').removeAttribute('style');
					}
					else{
						document.getElementById('leaveDurationLabel').innerHTML = "Leave Duration (Exceed the leave duration)";
						document.getElementById('leaveDurationLabel').setAttribute('style', 'color:red;');
					}
				}


			}
		}
	</script>
@endsection
