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
								@if ($leaveType->leaveType != "Carried Forward Leave")
									<option value="{{ $leaveType->id }}" data-leaveID="{{ $leaveType->id }}" data-leaveType="{{ $leaveType->leaveType }}" data-leaveLimit="{{ $leaveType->leaveLimit }}" {{ (old('leaveType') == $leaveType->id ? "selected": null) }}>{{ ucfirst($leaveType->leaveType) }}</option>
								@endif

								@if (count($carriedForwardLeaves) > 0 && $leaveType->leaveType == "Carried Forward Leave")
									@foreach ($carriedForwardLeaves as $carriedForwardLeave)
										<option value="{{ $leaveType->id }}" data-leaveID="{{ $leaveType->id }}" data-leaveType="{{ $leaveType->leaveType }}" data-leaveLimit="{{ $carriedForwardLeave->leaveLimit }}" {{ (old('leaveType') == $leaveType->id ? "selected": null) }}>{{ ucfirst($leaveType->leaveType) }}</option>
									@endforeach
								@endif
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
						<input class="form-control @error('leaveStartDate') form-control-danger @enderror" type="date" id="leaveStartDate" name="leaveStartDate" value="{{ old('leaveStartDate') }}" onblur="checkLeaveLimit();" required>
						
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
						<label id="leaveEndDateLabel">Leave End Date</label>
						<input class="form-control @error('leaveEndDate') form-control-danger @enderror" type="date" id="leaveEndDate" name="leaveEndDate" value="{{ old('leaveEndDate') }}" onblur="checkLeaveLimit2();" required>
						
						@error("leaveEndDate")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<label>Leave Period</label>
						<div class="custom-control custom-radio mb-5">
							<input type="radio" id="leavePeriod1" name="leavePeriod" value="Full Day" class="custom-control-input" required>
							<label class="custom-control-label" for="leavePeriod1">Full Day</label>
						</div>
						<div class="custom-control custom-radio mb-5">
							<input type="radio" id="leavePeriod2" name="leavePeriod" value="1st Half Day" class="custom-control-input">
							<label class="custom-control-label" for="leavePeriod2">1st Half Day</label>
						</div>
						<div class="custom-control custom-radio mb-5">
							<input type="radio" id="leavePeriod3" name="leavePeriod" value="2nd Half Day" class="custom-control-input">
							<label class="custom-control-label" for="leavePeriod3">2nd Half Day</label>
						</div>
						@error("leavePeriod")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			{{-- <div class="form-group">
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
			</div> --}}

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Leave Description <i>(Reason to apply this leave)</i></label>
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
				html: 'Leave deducted: {{ session("message1") }} days',
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
			checkLeaveLimit();
		});

		function checkLeaveLimit(){
			var leaveTypeInput = document.getElementById('leaveType');

			if(leaveTypeInput.value != ""){
				var currentYear = new Date();
				var leaveID = leaveTypeInput.options[leaveTypeInput.selectedIndex].getAttribute('data-leaveID');
				var leaveType = leaveTypeInput.options[leaveTypeInput.selectedIndex].getAttribute('data-leaveType');
				var leaveLimit = leaveTypeInput.options[leaveTypeInput.selectedIndex].getAttribute('data-leaveLimit');
				
				var totalApprovedLeave = 0;
				@foreach ($approvedLeaves as $approvedLeave)
					var approvedLeaveType = {{ $approvedLeave->leaveType }};
					var approvedLeaveDuration = {{ $approvedLeave->leaveDuration }};
					var approvedLeaveDate = new Date(Date.parse("{{ date("d F Y", strtotime($approvedLeave->leaveStartDate)) }}"));

					if((approvedLeaveType == leaveID) && (approvedLeaveDate.getFullYear() == currentYear.getFullYear())){
						totalApprovedLeave += approvedLeaveDuration;
					}
				@endforeach

				var remainingLeave = leaveLimit - totalApprovedLeave;
				if(leaveType == "Annual Leave"){
					var accountCreatedYear = new Date("{{ Auth::user()->created_at }}");

					if(accountCreatedYear.getFullYear() == currentYear.getFullYear()){
						var remainingMonthForThisYear = 12 - accountCreatedYear.getMonth();
						
						leaveLimit = (leaveLimit/12) * remainingMonthForThisYear;
						remainingLeave = parseInt(leaveLimit) - totalApprovedLeave;
					}
				}
				console.log(remainingLeave);

				if(remainingLeave == 0.5){
					document.getElementById("leavePeriod1").checked = false;
					document.getElementById("leavePeriod1").disabled = true;
					document.getElementById("leavePeriod2").disabled = false;
					document.getElementById("leavePeriod3").disabled = false;
					document.getElementById("leaveEndDate").value = document.getElementById('leaveStartDate').value;
					document.getElementById("leaveEndDate").disabled = true;
				}
				else{
					document.getElementById("leavePeriod1").disabled = false;
					document.getElementById("leaveEndDate").value = null;
					document.getElementById("leaveEndDate").disabled = false;
				}

				if(remainingLeave > 0.5){
					var leaveStartDate = new Date(document.getElementById('leaveStartDate').value);
					if(leaveStartDate != null){
						leaveStartDate.setDate(leaveStartDate.getDate() + remainingLeave);
		
						if((leaveStartDate.getMonth() + 1) < 10){
							var month = "0" + (leaveStartDate.getMonth() + 1);
						}
						else{
							var month = (leaveStartDate.getMonth() + 1);
						}
						if((leaveStartDate.getDate() - 1) < 10){
							var day = "0" + (leaveStartDate.getDate() - 1);
						}
						else{
							var day = (leaveStartDate.getDate() - 1);
						}
						var maxLeaveEndDate = leaveStartDate.getFullYear() + "-" + month + "-" + day;
						document.getElementById('leaveEndDate').value = null;
						document.getElementById('leaveEndDate').setAttribute('min', document.getElementById('leaveStartDate').value);
						document.getElementById('leaveEndDate').setAttribute('max', maxLeaveEndDate);
					}
				}
				//document.getElementById('leaveDuration').setAttribute('max', remainingLeave);

				/*var leaveDurationInput = document.getElementById('leaveDuration').value;

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
				}*/
			}
		}
		function checkLeaveLimit2() 
		{ 
			var leaveStartDate = document.getElementById('leaveStartDate').value;
				var leaveEndDate = document.getElementById('leaveEndDate').value;

				if(leaveStartDate != null && leaveEndDate != null){
					if(leaveStartDate != leaveEndDate){
						document.getElementById("leavePeriod1").checked = true;
						document.getElementById("leavePeriod2").checked = false;
						document.getElementById("leavePeriod3").checked = false;
						document.getElementById("leavePeriod1").disabled = false;
						document.getElementById("leavePeriod2").disabled = true;
						document.getElementById("leavePeriod3").disabled = true;
					}
					else{
						document.getElementById("leavePeriod1").checked = false;
						document.getElementById("leavePeriod2").checked = false;
						document.getElementById("leavePeriod3").checked = false;
						document.getElementById("leavePeriod1").disabled = false;
						document.getElementById("leavePeriod2").disabled = false;
						document.getElementById("leavePeriod3").disabled = false;
					}
				}
		 }
	</script>
@endsection
