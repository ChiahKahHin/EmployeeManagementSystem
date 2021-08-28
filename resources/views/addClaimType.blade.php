@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Add Claim Type
@endsection

@section('pageTitle')
	Add Claim Type
@endsection

@section('content')
<div class="pd-20 card-box mb-30">
	<div class="clearfix">
		<div class="pull-left mb-10">
			<h4 class="text-blue h4">Add Claim Type</h4>
		</div>
	</div>

	<form action="{{ route('addClaimType') }}" method="POST">
		@csrf
		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<label>Claim Category</label>
					<select class="form-control selectpicker @error('claimCategory') form-control-danger @enderror" name="claimCategory" required>
						<option value="" selected disabled hidden>Select claim category</option>
						@foreach ($claimCategories as $claimCategory)
							<option value="{{ $claimCategory->id }}" {{ (old('claimCategory') == $claimCategory->id ? "selected": null) }}>{{ ucfirst($claimCategory->claimCategory) }}</option>
						@endforeach
					</select>
					
					@error("claimCategory")
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
					<label>Claim Type</label>
					<input class="form-control @error('claimType') form-control-danger @enderror" type="text" name="claimType" placeholder="Enter claim type" value="{{ old('claimType') }}" required>
					
					@error("claimType")
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
					<label>Claim Type Amount</label>
					<input class="form-control @error('claimAmount') form-control-danger @enderror" type="number" min="0" step="1" name="claimAmount" placeholder="Enter claim amount" value="{{ old('claimAmount') }}" required>
					
					@error("claimAmount")
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
					<label>Claim Period</label>
					@php
						$claimPeriods = array("Per Annum", "Per Claim");
					@endphp
					<select class="form-control selectpicker @error('claimPeriod') form-control-danger @enderror" name="claimPeriod" required>
						<option value="" selected disabled hidden>Select claim period</option>
						@foreach ($claimPeriods as $claimPeriod)
							<option value="{{ $claimPeriod }}" {{ (old('claimPeriod') == $claimPeriod ? "selected": null) }}>{{ $claimPeriod }}</option>
						@endforeach
					</select>
					
					@error("claimPeriod")
						<div class="text-danger text-sm">
							{{ $message }}
						</div>
					@enderror	
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<button type="submit" class="btn btn-primary btn-block">Add Claim Type</button>
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
