<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>EMS | Reset Password Page</title>

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}">


	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>
</head>

<body>
	<div class="login-header box-shadow">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<h3 class="text-primary" style="padding-top: 1rem; padding-left: 1rem">Employee Management System</h3>
			</div>
		</div>
	</div>
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6">
					<img src="{{ asset('vendors/images/forgot-password.png') }}" alt="">
				</div>
				<div class="col-md-6">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Reset Password</h2>
						</div>
						<form action="{{ route("password.reset", ['token' => $token]) }}" method="POST">
							@csrf
							<input type="hidden" name="token" id="token" value="{{ $token }}">

							<h6 class="mb-20">Enter your email address</h6>
							<div class="input-group custom" @error("email") style="margin-bottom: 0;" @enderror>
								<input type="email" name="email" autocomplete="off" class="form-control form-control-lg @error("email") border-danger @enderror" value="{{ old('email') }}" placeholder="Email address">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="fa fa-envelope-o"></i></span>
								</div>
							</div>
							@error("email")
								<div class="text-danger text-sm" style="margin-bottom: 25px;">
									{{ $message }}
								</div>
							@enderror

							<h6 class="mb-20">Enter your new password</h6>
							<div class="input-group custom"  @error("password") style="margin-bottom: 0;" @enderror>
								<input type="password" name="password" class="form-control form-control-lg @error("password") border-danger @enderror" placeholder="**********">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>
							@error("password")
							<div class="text-danger text-sm" style="margin-bottom: 25px;">
								{{ $message }}
							</div>
							@enderror

							<h6 class="mb-20">Enter your confirm new password</h6>
							<div class="input-group custom"  @error("password_confirmation") style="margin-bottom: 0;" @enderror>
								<input type="password" name="password_confirmation" class="form-control form-control-lg @error("password_confirmation") border-danger @enderror" placeholder="**********">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										<input class="btn btn-primary btn-lg btn-block" type="submit" value="Reset Password">
									</div>
								</div>
							</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- js -->
	<script src="{{ asset('vendors/scripts/core.js') }}"></script>
	<script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
	<script src="{{ asset('vendors/scripts/process.js') }}"></script>
	<script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
</body>

</html>