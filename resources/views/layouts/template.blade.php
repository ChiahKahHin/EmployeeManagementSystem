<!DOCTYPE html>
<html>
	<head>
		<title>@yield("title")</title>
		
		@include("layouts.head")
	</head>
	<body>
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