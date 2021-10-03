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
						<label>Able to carry forward annual leave?</label>
						<input class="form-control switch-btn" type="checkbox" id="ableCF" name="ableCF" onchange="showConfiguration();" data-size="small" data-color="#0099ff" value="1" {{ (old('ableCF', $rule->ableCF)? "checked" : null) }}>
					</div>
				</div>
			</div>

			<div id="configuration" style="display: none;">
				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<label>Same configuration in the future? <i>(Recurring yearly)</i></label>
							<input class="form-control switch-btn" type="checkbox" id="recurring" name="recurring" data-size="small" data-color="#0099ff" value="1" {{ (old('ableCF', $rule->recurring)? "checked" : null) }}>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<label>Maximum limit of annual leave that can be carried forward? (days)</label>
							<input class="form-control @error('leaveLimit') form-control-danger @enderror" type="number" min="1" max="{{ $annualLeaveLimit->leaveLimit }}" id="leaveLimit" name="leaveLimit" placeholder="Enter number of days" value="{{ old('leaveLimit', $rule->leaveLimit) }}">
							
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
							<input class="form-control @error('useBefore') form-control-danger @enderror" type="date" min="@php echo date("Y-01-01", strtotime("+1 year")) @endphp" max="@php echo date("Y-12-31", strtotime("+1 year")) @endphp" id="useBefore" name="useBefore" placeholder="Select use before" value="{{ old('useBefore', $rule->useBefore) }}">
							
							@error("useBefore")
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
							<input class="form-control switch-btn" type="checkbox" id="approval" name="approval" onchange="showPeriod();" data-size="small" data-color="#0099ff" value="1" {{ (old('approval', $rule->approval)? "checked" : null) }}>
						</div>
					</div>
				</div>

				<div id="period" style="display: none;">
					<h6>Period for employee to submit request</h6><br>
					<div class="form-group">
						<div class="row">
							<div class="col-md-3">
								<label>Start Date</label>
								<input class="form-control @error('startDate') form-control-danger @enderror" type="date" min="@php echo date("Y-m-d") @endphp" max="@php echo date("Y-12-31") @endphp" id="startDate" name="startDate" onblur="updateEndDate();" placeholder="Select use before" value="{{ old('startDate', $rule->startDate) }}">
								
								@error("startDate")
									<div class="text-danger text-sm">
										{{ $message }}
									</div>
								@enderror
							</div>
							<div class="col-md-3">
								<label>End Date</label>
								<input class="form-control @error('endDate') form-control-danger @enderror" type="date" min="{{ $rule->startDate }}" max="@php echo date("Y-12-31") @endphp" id="endDate" name="endDate" placeholder="Select use before" value="{{ old('endDate', $rule->endDate) }}">
								
								@error("endDate")
									<div class="text-danger text-sm">
										{{ $message }}
									</div>
								@enderror
							</div>
						</div>
						<br>
					</div>
				</div>

				<div id="approvalMessage" style="display: none;">
					<div class="row">
						<div class="col-md-6">
							<h6 class=" text-justify"><i>The annual leave that does not applied by the employees will be carry forward in the year end automatically, based on the configuration above</i></h6><br>
						</div>
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
			showConfiguration();
			showPeriod();
		});

		function updateEndDate() {
			document.getElementById('endDate').value = null;
			console.log(document.getElementById('startDate').value);
			document.getElementById('endDate').setAttribute('min', document.getElementById('startDate').value);
		}

		function showConfiguration() {
			var checked = document.getElementById('ableCF').checked;

			if(checked == true){
				document.getElementById('configuration').removeAttribute('style');
				document.getElementById('leaveLimit').setAttribute('required', '');
				document.getElementById('useBefore').setAttribute('required', '');
			}
			else{
				document.getElementById('configuration').setAttribute('style', 'display:none;');
				document.getElementById('leaveLimit').removeAttribute('required');
				document.getElementById('useBefore').removeAttribute('required');
			}
		}

		function showPeriod() {
			var checked = document.getElementById('approval').checked;

			if(checked == true){
				document.getElementById('period').removeAttribute('style');
				document.getElementById('startDate').setAttribute('required', '');
				document.getElementById('endDate').setAttribute('required', '');
				document.getElementById('approvalMessage').setAttribute('style', 'display:none;');
			}
			else{
				document.getElementById('period').setAttribute('style', 'display:none;');
				document.getElementById('startDate').removeAttribute('required');
				document.getElementById('endDate').removeAttribute('required');
				document.getElementById('approvalMessage').removeAttribute('style');
			}
		}

	</script>
@endsection