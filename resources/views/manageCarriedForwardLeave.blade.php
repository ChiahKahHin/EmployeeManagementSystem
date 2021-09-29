@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Carried Forward Leave Configuration
@endsection

@section('pageTitle')
	Carried Forward Leave Configuration
@endsection

@section('content')
	<div class="pd-20 card-box mb-30">
		<div class="clearfix">
			<div class="pull-left mb-10">
				<h4 class="text-blue h4">Carried Forward Leave Configuration ({{ date('Y', strtotime('+1 year')) }})</h4>
			</div>
		</div>

		<form action="{{ route('manageCarriedForwardLeave') }}" method="POST">
			@csrf
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Annual leave that can be carried forward? (days)</label>
						<input class="form-control @error('leaveLimit') form-control-danger @enderror" type="number" min="1" max="{{ $annualLeaveLimit->leaveLimit }}" name="leaveLimit" placeholder="Enter number of days" value="{{ old('leaveLimit', $rule->leaveLimit) }}" required>
						
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
						<label>Carried Forward Leave Expired After?</label>
						<input class="form-control @error('useBefore') form-control-danger @enderror" type="date" min="@php echo date("Y-01-01", strtotime("+1 year")) @endphp" max="@php echo date("Y-12-31", strtotime("+1 year")) @endphp" name="useBefore" placeholder="Select use before" value="{{ old('useBefore', $rule->useBefore) }}">
						
						@error("useBefore")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
				<br>
			</div>
			<h6>Period for employee to submit request</h6><br>
			<div class="form-group">
				<div class="row">
					<div class="col-md-3">
						<label>Start Date</label>
						<input class="form-control @error('startDate') form-control-danger @enderror" type="date" min="@php echo date("Y-m-d") @endphp" max="@php echo date("Y-12-31") @endphp" id="startDate" name="startDate" onblur="updateEndDate();" placeholder="Select use before" value="{{ old('startDate', $rule->startDate) }}" required>
						
						@error("startDate")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
					<div class="col-md-3">
						<label>End Date</label>
						<input class="form-control @error('endDate') form-control-danger @enderror" type="date" min="{{ $rule->startDate }}" max="@php echo date("Y-12-31") @endphp" id="endDate" name="endDate" placeholder="Select use before" value="{{ old('endDate', $rule->endDate) }}" required>
						
						@error("endDate")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
				<br>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Need manager's approval?</label>
						<input class="form-control switch-btn" type="checkbox" id="approval" name="approval" data-size="small" data-color="#0099ff" value="1" {{ (old('approval', $rule->approval)? "checked" : null) }}>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Update C/F Leave Configuration</button>
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


@section("script")
	<script>
		$(document).ready(function() {
			//updateEndDate();				
		});

		function updateEndDate() {
			document.getElementById('endDate').value = null;
			console.log(document.getElementById('startDate').value);
			document.getElementById('endDate').setAttribute('min', document.getElementById('startDate').value);
		}

	</script>
@endsection