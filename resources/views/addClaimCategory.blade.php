@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Add Claim Category
@endsection

@section('pageTitle')
	Add Claim Category
@endsection

@section('content')
<div class="pd-20 card-box mb-30">
	<div class="clearfix">
		<div class="pull-left mb-10">
			<h4 class="text-blue h4">Add Claim Category</h4>
		</div>
	</div>

	<form action="{{ route('addClaimCategory') }}" method="POST">
		@csrf
		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<label>Claim Category</label>
					<input class="form-control @error('claimCategory') form-control-danger @enderror" type="text" name="claimCategory" placeholder="Enter claim category (must be unique)" value="{{ old('claimCategory') }}" required>
					
					@error("claimCategory")
						<div class="text-danger text-sm">
							{{ $message }}
						</div>
					@enderror
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<button type="submit" class="btn btn-primary btn-block">Add Claim Category</button>
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
