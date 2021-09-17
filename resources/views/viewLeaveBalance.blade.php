@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | All Leave Balance
@endsection

@section('pageTitle')
	All Leave Balance
@endsection

@section('content')
	<div class="card-box mb-30">
		<div class="pd-20">
			<h4 class="text-blue h4">All Leave Balance
				<a href="{{ route('applyLeave') }}" style="float: right" class="btn btn-outline-primary">
                    <i class="icon-copy dw dw-add"></i> Apply Leave
                </a>
			</h4>
		</div>
		<div class="pd-20">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th width="5%">#</th>
						<th style="cursor: pointer;" data-container="body" data-toggle="popover" data-placement="top" data-content="This is all the leave types that available" title="Leave Type">Leave Type <i class="icon-copy dw dw-question text-primary"></i></th>
						<th style="cursor: pointer;" data-container="body" data-toggle="popover" data-placement="top" data-content="This is the total days that can be apply in {{ date('Y') }}" title="Leave Entitlement">Leave Entitlement (Days)  <i class="icon-copy dw dw-question text-primary"></i></th>
						<th style="cursor: pointer;" data-container="body" data-toggle="popover" data-placement="top" data-content="This is the approved leave days" title="Leave Applied">Leave Applied (Days)  <i class="icon-copy dw dw-question text-primary"></i></th>
						<th style="cursor: pointer;" data-container="body" data-toggle="popover" data-placement="top" data-content="This is the remaining leave that can be apply in {{ date('Y') }}" title="Leave Balance">Leave Balance (Days)  <i class="icon-copy dw dw-question text-primary"></i></th>
					</tr>
				</thead>
				<tbody>
					@php
						$iteration = 1;
					@endphp
					@foreach ($leaveTypes as $leaveType)
						@if ($leaveType->id != 2)
							<tr>
								<td>{{ $iteration }}</td>
								<td class="table-plus">{{ $leaveType->leaveType }}</td>
								@php
									$leaveLimit = $leaveType->leaveLimit;
									$totalApprovedLeave = 0;
									foreach ($approvedLeaves as $approvedLeave){
										if($approvedLeave->leaveType == $leaveType->id){
											$totalApprovedLeave += $approvedLeave->leaveDuration;
										}
									}

									if($leaveType->id == 1 && Auth::user()->created_at->year == date('Y')){
										$remainingMonthForThisYear = (12 - Auth::user()->created_at->month) + 1;
										$leaveLimit = ($leaveType->leaveLimit / 12) * $remainingMonthForThisYear;
									}
									
								@endphp
								<td>{{ intval($leaveLimit) }} </td>
								<td>{{ $totalApprovedLeave }}</td>
								<td>{{ intval($leaveLimit)-$totalApprovedLeave }}</td>
							</tr>
							@php
								$iteration++;
							@endphp
						@else
							@if (count($carriedForwardLeaves) > 0)
								<tr>
									<td>{{ $iteration }}</td>
									<td class="table-plus">{{ $leaveType->leaveType }}</td>
									@php
										foreach ($carriedForwardLeaves as $carriedForwardLeave) {
											$leaveLimit = $carriedForwardLeave->leaveLimit;
										}
										$totalApprovedLeave = 0;
										foreach ($approvedLeaves as $approvedLeave){
											if($approvedLeave->leaveType == $leaveType->id){
												$totalApprovedLeave += $approvedLeave->leaveDuration;
											}
										}									
									@endphp
									<td>{{ intval($leaveLimit) }} </td>
									<td>{{ $totalApprovedLeave }}</td>
									<td>{{ intval($leaveLimit)-$totalApprovedLeave }}</td>
								</tr>
								@php
									$iteration++;
								@endphp
							@endif
						@endif
					@endforeach
					
				</tbody>
			</table>
		</div>
	</div>
@endsection