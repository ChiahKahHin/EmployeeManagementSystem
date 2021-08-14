@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Edit Benefit Claim
@endsection

@section('pageTitle')
	Edit Benefit Claim
@endsection

@section('content')
<div class="pd-20 card-box mb-30">
	<div class="clearfix">
		<div class="pull-left mb-10">
			<h4 class="text-blue h4">Edit Benefit Claim</h4>
		</div>
	</div>

	<form action="{{ route('editBenefitClaim', ['id' => $benefitClaim->id]) }}" method="POST">
		@csrf
		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<label>Benefit Claim Type</label>
					<input class="form-control @error('claimType') form-control-danger @enderror" type="text" name="claimType" placeholder="Enter benefit claim type" value="{{ old('claimType', $benefitClaim->claimType) }}" required>
					
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
					<label>Benefit Claim Amount <i>(Per Annum)</i></label>
					<input class="form-control @error('claimAmount') form-control-danger @enderror" type="number" min="0" step="1" name="claimAmount" placeholder="Enter benefit claim amount" value="{{ old('claimAmount', $benefitClaim->claimAmount) }}" required>
					
					@error("claimAmount")
						<div class="text-danger text-sm">
							{{ $message }}
						</div>
					@enderror
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<button type="submit" class="btn btn-primary btn-block">Edit Benefit Claim</button>
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
@endsection
