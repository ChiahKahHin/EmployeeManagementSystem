<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>EMS | Login Page</title>

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>
</head>
<body class="login-page">
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
				<div class="col-md-6 col-lg-7">
					<img src="vendors/images/login-page-img.png" alt="">
				</div>
				<div class="col-md-6 col-lg-5">
					<div class="login-box bg-white box-shadow border-radius-10">
						
						@if (session("message"))
							<p class="text-danger" style="padding-bottom: 10px;">{{ session("message") }}</p>
						@endif
						@if (session("resetPasswordMessage"))
							<p class="text-success" style="padding-bottom: 10px;">{{ session("resetPasswordMessage") }}</p>
						@endif
						<form action="{{ route('login') }}" method="POST">
							@csrf
							<div class="input-group custom" @error("username") style="margin-bottom: 0;" @enderror>
								<input type="text" name="username" class="form-control form-control-lg @error("username") border-danger @enderror" value="{{ old('username') }}" placeholder="Username" required>
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
								</div>
							</div>
							@error("username")
								<div class="text-danger text-sm" style="margin-bottom: 25px;">
									{{ $message }}
								</div>
							@enderror

							<div class="input-group custom"  @error("password") style="margin-bottom: 0;" @enderror>
								<input type="password" name="password" class="form-control form-control-lg @error("password") border-danger @enderror" placeholder="**********" required>
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>
							@error("password")
							<div class="text-danger text-sm" style="margin-bottom: 25px;">
								{{ $message }}
							</div>
							@enderror

							<div class="row pb-30">
								<div class="col-6">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" id="customCheck1" name="remember">
										<label class="custom-control-label" for="customCheck1">Remember Me</label>
									</div>
								</div>
								<div class="col-6">
									<div class="forgot-password"><a href="{{ route('forgetPassword') }}">Forgot Password?</a></div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										<input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
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
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
</body>
</html>