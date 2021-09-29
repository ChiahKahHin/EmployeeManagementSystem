@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Carried Forward Leave Application
@endsection

@section('pageTitle')
	Carried Forward Leave Application
@endsection

@section('content')
	@php
		$totalApprovedLeave = 0;
		foreach ($approvedLeaves as $approvedLeave) {
			$totalApprovedLeave += $approvedLeave->leaveDuration;
		}

		$leaveLimit = $oriAnnualLeave->leaveLimit;
		if(Auth::user()->created_at->year == date('Y')){
			$remainingMonthForThisYear = (12 - Auth::user()->created_at->month) + 1;
			$leaveLimit = ($oriAnnualLeave->leaveLimit / 12) * $remainingMonthForThisYear;
		}
		$actualRemainingLeave = intval($leaveLimit) - $totalApprovedLeave;
		
		@endphp
	@if ($actualRemainingLeave == 0)
		<script>
			swal({
				title: 'Warning',
				html: 'Your annual leave is fully applied in the current year',
				type: 'warning',
				confirmButtonClass: 'btn btn-danger',
			}).then(function(){
				window.location.href = "/";
			});
		</script>
	@endif
	@php
		if($actualRemainingLeave > $rule->leaveLimit){
			$actualRemainingLeave = $rule->leaveLimit;
		}
	@endphp
	<div class="pd-20 card-box mb-30">
		<div class="clearfix">
			<div class="pull-left mb-10">
				<h4 class="text-blue h4">Carried Forward Leave Application</h4>
			</div>
		</div>

		<form action="{{ route('applyCarriedForwardLeave') }}" method="POST">
			@csrf
			<div class="form-group">
				<div class="col-md-6" style="border-style: solid;">
					<div class="row">
						<label><b>Total Annual Leave ({{ date('Y') }}): {{ intval($leaveLimit) }} days</b></label>
					</div>
				
					<div class="row">
						<label><b>Total Approved Annual Leave ({{ date('Y') }}): {{ $totalApprovedLeave }} days</b></label>
					</div>
				
					<div class="row">
						<label><b>Annual Leave Balance ({{ date('Y') }}): {{ intval($leaveLimit) - $totalApprovedLeave }} days</b></label>
					</div>
				
					<div class="row">
						<label><b>Annual Leave That Can Carried Forward: {{ $actualRemainingLeave }} days</b></label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Entitled Carried Forward Leave ({{ date('Y', strtotime("+1 years")) }})</label>
						<input class="form-control @error('leaveLimit') form-control-danger @enderror" type="number" step=".5" min="1" max="{{ $actualRemainingLeave }}" name="leaveLimit" placeholder="Enter leave limit" value="{{ old('leaveLimit', $actualRemainingLeave) }}" required>
						
						@error("leaveLimit")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Apply Carried Forward Leave</button>
				</div>
			</div>
		</form>
	</div>
@endsection
