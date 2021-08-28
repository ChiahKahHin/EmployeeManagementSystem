@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Manage Working Day
@endsection

@section('pageTitle')
	Manage Working Day
@endsection

@section('content')
<div class="pd-20 card-box mb-30">
	<div class="clearfix">
		<div class="pull-left mb-10">
			<h4 class="text-blue h4">Manage Working Day</h4>
		</div>
	</div>

	<form action="{{ route('manageWorkingDay') }}" method="POST">
		@csrf
		<div class="form-group">
			<div class="row">
				<div class="col-md-6 col-sm-12">
					@foreach ($workingDays as $workingDay)
						<div class="custom-control custom-checkbox mb-5">
							<input type="checkbox" class="custom-control-input" id="{{ $workingDay->id }}" name="workingDay[]" value="{{ $workingDay->id }}" @if($workingDay->status == 1) checked @endif>
							<label class="custom-control-label" for="{{ $workingDay->id }}">{{ $workingDay->workingDay }}</label>
						</div>
					@endforeach
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<button type="submit" class="btn btn-primary btn-block">Update Working Day</button>
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
			type: 'error',
			confirmButtonClass: 'btn btn-success',
			//timer:5000
		});
	</script>
@endif
@endsection
