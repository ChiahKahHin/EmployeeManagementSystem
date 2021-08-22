@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Leave Calendar
@endsection

@section('pageTitle')
	Leave Calendar
@endsection

@section('content')
	<div class="pd-20 card-box mb-30">
		<div class="clearfix mb-30">
			<div class="pull-left">
				<h4 class="text-blue h4">Leave Calendar</h4>
			</div>
		</div>
		<div class="calendar-wrap">
			<div id='calendar'></div>
		</div>
		<!-- calendar modal -->
		<div id="modal-view-event" class="modal modal-top fade calendar-modal">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-body">
						<h4 class="h4"><span class="event-icon weight-400 mr-3"></span><span class="event-title"></span></h4>
						<div class="event-body"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section("script")
	<script>
		//refer calendar-setting.js
		$(document).ready(function() {
			$('#calendar').fullCalendar({
				themeSystem: 'bootstrap4',
				businessHours: false,
				defaultView: 'month',
				editable: false, //event dragging & resizing
				header: {
					left: 'prev,next today',
					center: 'title',
					right:'prev,next today'
				},
				events: [
					@foreach ($publicHolidays as $publicHoliday)
						{
							title: 'PH - {{ $publicHoliday->name }}',
							description: 'All employees will be off',
							start: '{{ $publicHoliday->date }}',
							end: '{{ $publicHoliday->date }}',
							className: 'fc-bg-default',
							icon : "calendar"
						},
					@endforeach

					@foreach ($leaveRequests as $leaveRequest)
						{
							title: '{{ $leaveRequest->getLeaveType->leaveType }} - {{ $leaveRequest->getEmployee->getFullName() }}',
							description: '{{ $leaveRequest->leaveDescription }}',
							start: '{{ $leaveRequest->leaveStartDate }}',
							end: '{{ date("Y-m-d", strtotime("+1 days", strtotime($leaveRequest->leaveEndDate))) }}',
							className: 'fc-bg-blue',
							icon : "calendar"
						},
					@endforeach

				],
				// eventClick: function(event, jsEvent, view) {
				// 	swal("Cancelled", "Training program is not registered", "error");
				// },
				eventClick: function(event, jsEvent, view) {
					jQuery('.event-icon').html("<i class='fa fa-"+event.icon+"'></i>");
					jQuery('.event-title').html(event.title);
					jQuery('.event-body').html(event.description);
					jQuery('.eventUrl').attr('href',event.url);
					jQuery('#modal-view-event').modal();
				},
			});
		});
	</script>
@endsection