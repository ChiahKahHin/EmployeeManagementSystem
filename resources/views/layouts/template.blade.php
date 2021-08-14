<!DOCTYPE html>
<html>
	<head>
		<title>@yield("title")</title>
		
		@include("layouts.head")
	</head>
	<body>
		{{-- <div class="pre-loader">
			<div class="pre-loader-box">
				<div class='loader-progress' id="progress_div">
					<div class='bar' id='bar1'></div>
				</div>
				<div class='percent' id='percent1'>0%</div>
				<div class="loading-text">
					System is loading...
				</div>
			</div>
		</div> --}}
		@include("layouts.nav")

		@include("layouts.sidebar")
		
		<div class="mobile-menu-overlay"></div>
			
		<div class="main-container">
			<div class="pd-ltr-20">
				@yield("content")

				@include("layouts.footer")
			</div>
		</div>

		@include("layouts.script")

		@yield("script")
	</body>
</html>