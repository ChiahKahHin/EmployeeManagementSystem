@php
	$currentRouteName = Route::currentRouteName();
@endphp

<!-- Core JS -->
<script src="{{ asset('vendors/scripts/core.js') }}"></script>
<script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
<script src="{{ asset('vendors/scripts/process.js') }}"></script>
<script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>

<!-- Datatables -->
@php
	$datatableRoutes = 
	[
		'manageDepartment', 'managePosition', 'manageEmployee', 'manageTask', 'manageClaimCategory',
		'manageClaimType', 'manageClaimRequest', 'manageMemo', 'manageTrainingProgram', 'managePublicHoliday',
		'manageLeaveType', 'manageLeave', 'manageDelegation', 'viewTrainingProgram'
	]
@endphp
@if (in_array($currentRouteName, $datatableRoutes))
	<script src="{{ asset('src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
	<script src="{{ asset('src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
	<script src="{{ asset('src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>
	
	<!-- Datatables Setting js -->
	<script src="{{ asset('vendors/scripts/datatable-setting.js') }}"></script>
@endif

<!-- Switchery -->
@php
	$switcheryRoutes = 
	[
		'addEmployee', 'addLeaveType', 'addTask', 'addTrainingProgram', 'createMemo',
		'editLeaveType', 'editMemo', 'editTrainingProgram'
	]
@endphp
@if (in_array($currentRouteName, $switcheryRoutes))
	<script src="{{ asset('src/plugins/switchery/switchery.min.js') }}"></script>
	<script src="{{ asset('vendors/scripts/advanced-components.js') }}"></script>
@endif

<!-- bootstrap-tagsinput js -->
{{-- <script src="{{ asset('src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script> --}}

<!-- bootstrap-touchspin js -->
{{-- <script src="{{ asset('src/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.js') }}"></script> --}}

<!-- Apexchart -->
@php
	$apexChartRoutes = 
	[
		'dashboard1', 'taskAnalyticsPage', 'taskAnalyticsPage2', 'taskAnalyticsPage3', 'claimAnalytics',
		'claimAnalytics2', 'claimAnalytics3', 'trainingAnaytics', 'trainingAnaytics2', 'leaveAnalytics',
		'leaveAnalytics2', 'leaveAnalytics3'
	]
@endphp
@if (in_array($currentRouteName, $apexChartRoutes))
	<script src="{{ asset('src/plugins/apexcharts/apexcharts.min.js') }}"></script>	
@endif

<!-- Full Calendar -->
@php
	$fullcalendarRoutes = 
	[
		'leaveCalendar',
	]
@endphp
@if (in_array($currentRouteName, $fullcalendarRoutes))
	<script src="{{ asset('src/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
@endif

<!-- Buttons for Export datatable -->
{{-- <script src="{{ asset('src/plugins/datatables/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/vfs_fonts.js') }}"></script> --}}