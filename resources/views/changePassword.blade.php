@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Change Password
@endsection

@section('pageTitle')
	Change Password
@endsection

@section('content')
	<div class="pd-20 card-box mb-30">
		<div class="clearfix">
			<div class="pull-left mb-10">
				<h4 class="text-blue h4">Change Password</h4>
			</div>
		</div>

		<form action="{{ route('changePassword') }}" method="POST">
			@csrf
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Old Password</label>
						<input class="form-control @error('oldPassword') form-control-danger @enderror" type="password" name="oldPassword" placeholder="Enter old password" value="{{ old('oldPassword') }}" required>
						
						@error("oldPassword")
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
						<label>New Password</label>
						<input class="form-control @error('password') form-control-danger @enderror" type="password" name="password" placeholder="Enter new password" value="{{ old('password') }}" required>
						
						@error("password")
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
						<label>Confirm New Password</label>
						<input class="form-control @error('password_confirmation') form-control-danger @enderror" type="password" name="password_confirmation" placeholder="Enter confirm new password" value="{{ old('password_confirmation') }}" required>
						
						@error("password_confirmation")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Change Password</button>
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
