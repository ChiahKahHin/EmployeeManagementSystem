@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Add Approval Delegation
@endsection

@section('pageTitle')
	Add Approval Delegation
@endsection

@section('content')
	<div class="pd-20 card-box mb-30">
		<div class="clearfix">
			<div class="pull-left mb-10">
				<h4 class="text-blue h4">Add Approval Delegation</h4>
			</div>
		</div>
		<form action="{{ route('addDelegation') }}" method="POST">
			@csrf
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Delegate Manager</label>

						<select class="form-control selectpicker @error('delegateManagerID') form-control-danger @enderror" id="delegateManagerID" name="delegateManagerID" required>
							<option value="" selected disabled hidden>Select Delegate Manager</option>
							@foreach ($managers as $manager)
								<option value="{{ $manager->id }}" {{ (old('delegateManagerID') == $manager->id ? "selected": null) }}>{{ ucfirst($manager->getFullName()) }}</option>
							@endforeach
						</select>

						@error("delegateManagerID")
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
						<label>Delegation Start Date</label>
						<input class="form-control @error('startDate') form-control-danger @enderror" type="date" id="startDate" name="startDate" min="{{ date('Y-m-d') }}" value="{{ old('startDate') }}" onblur="updateEndDate();" required>
						
						@error("startDate")
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
						<label>Delegation End Date</label>
						<input class="form-control @error('endDate') form-control-danger @enderror" type="date" id="endDate" name="endDate" value="{{ old('endDate') }}" required>
						
						@error("endDate")
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
						<label>Reason of Delegation</i></label>
						<input class="form-control @error('reason') form-control-danger @enderror" type="text" name="reason" placeholder="Enter reason of delegation" maxlength="255" value="{{ old('reason') }}" required>
						
						@error("reason")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Add Approval Delegation</button>
				</div>
			</div>
		</form>
	</div>
	@if (session('message'))
		<script>
			swal({
				title: '{{ session("message") }}',
				html: '{{ session("message1") }}',
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

@section('script')
	<script>
		function updateEndDate() {
			document.getElementById('endDate').setAttribute('min', document.getElementById('startDate').value);
			document.getElementById('endDate').value = null;
		}
	</script>
@endsection
