<!-- Basic Page Info -->
<meta charset="utf-8">

<!-- Site favicon -->
{{-- <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png"> --}}

<!-- Mobile Specific Metas -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}">
@php
	$currentRouteName = Route::currentRouteName();
@endphp

<!-- Datatables -->
@php
	$datatablesRoutes = 
	[
		'manageDepartment', 'managePosition', 'manageEmployee', 'manageTask', 'manageClaimCategory',
		'manageClaimType', 'manageClaimRequest', 'manageMemo', 'manageTrainingProgram', 'managePublicHoliday',
		'manageLeaveType', 'manageLeave', 'manageDelegation', 'viewTrainingProgram'
	]
@endphp
@if (in_array($currentRouteName, $datatablesRoutes))
	<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/datatables/css/responsive.bootstrap4.min.css') }}">
	
@endif

<link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}">
@php
	$customCSSRoutes = 
	[
		'viewClaimRequest', 'viewDelegation', 'viewLeave', 'viewTask', 'editTrainingProgram',
		'viewClaimRequest', 'viewTrainingProgram', 'viewTrainingProgram2'
	]
@endphp
@if (in_array($currentRouteName, $customCSSRoutes))
	<link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/custom.css') }}">
@endif

@php
	$fullcalendarRoutes = 
	[
		'leaveCalendar',
	]
@endphp
@if (in_array($currentRouteName, $fullcalendarRoutes))
	<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/fullcalendar/fullcalendar.css') }}">
@endif

<!-- switchery css -->
@php
	$switcheryRoutes = 
	[
		'addEmployee', 'addLeaveType', 'addTask', 'addTrainingProgram', 'createMemo',
		'editLeaveType', 'editMemo', 'editTrainingProgram'
	]
@endphp
@if (in_array($currentRouteName, $switcheryRoutes))
	<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/switchery/switchery.min.css') }}">
@endif

<!-- bootstrap-tagsinput css -->
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}"> --}}

<!-- bootstrap-touchspin css -->
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.css') }}"> --}}
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}"> --}}

<!-- Sweet Alert -->
@php
	$sweetAlertRoutes = 
	[
		'addClaimCategory', 'addClaimType', 'addDelegation', 'addDepartment', 'addEmployee',
		'addLeaveType', 'addPosition', 'addPublicHoliday', 'addTask', 'addTrainingProgram',
		'applyBenefitClaim', 'applyLeave', 'changePassword', 'createMemo', 'editClaimCategory',
		'editClaimType', 'editDepartment', 'editEmployee', 'editLeaveType', 'editMemo',
		'editPosition', 'editPublicHoliday', 'editTask', 'editTrainingProgram', 'manageClaimCategory',
		'manageClaimRequest', 'manageClaimType', 'manageDelegation', 'manageDepartment', 'manageEmployee',
		'manageLeave', 'manageLeaveType', 'manageMemo', 'managePosition', 'managePublicHoliday',
		'manageTask', 'manageTrainingProgram', 'manageWorkingDay', 'vuewClaimRequest', 'viewLeave',
		'viewProfile', 'viewTask', 'viewTrainingProgram2', 'taskAnalyticsPage', 'taskAnalyticsPage2',
		'taskAnalyticsPage3', 'leaveAnalytics', 'leaveAnalytics2', 'leaveAnalytics3', 'claimAnalytics',
		'claimAnalytics2', 'claimAnalytics3', 'trainingAnaytics', 'trainingAnaytics2'
	]
@endphp
@if (in_array($currentRouteName, $sweetAlertRoutes))
	<script src="{{ asset('src/plugins/sweetalert2/sweetalert2.all.js') }}"></script>
@endif

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-119386393-1');
</script>
