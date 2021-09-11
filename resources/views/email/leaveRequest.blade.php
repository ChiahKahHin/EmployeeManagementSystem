@component('mail::message')
@php
	if($leaveRequest->delegateManagerID == null){
		$managerName = $leaveRequest->getManager->getFullName();
	}
	else{
		$managerName = $leaveRequest->getDelegateManager->getFullName();
	}
@endphp
@if ($leaveRequest->leaveStatus == 0)
Dear {{ $managerName }},

A new leave request from {{ $leaveRequest->getEmployee->getFullName() }} is waiting approval. 

@elseif($leaveRequest->leaveStatus == 1)
Dear {{ $leaveRequest->getEmployee->getFullName() }},

Your leave request is rejected.

Reason of leave request rejected: {{ $reason }} 

@elseif($leaveRequest->leaveStatus == 2)
Dear {{ $leaveRequest->getEmployee->getFullName() }},

Your leave request is approved.

@elseif($leaveRequest->leaveStatus == 3)
Dear {{ $managerName }},

This leave request is cancelled.

@elseif($leaveRequest->leaveStatus == 4)
Dear {{ $managerName }},

This leave request is cancelled after approval.
@endif

<u><b>Leave Request Details</b></u>

@component('mail::table')
| Leave Type | Leave Start Date | Leave End Date | Leave Status |
|:-----:|:--------:|:--------:|:------:|
| {{ $leaveRequest->getLeaveType->leaveType }} | {{  date("d F Y", strtotime($leaveRequest->leaveStartDate)) }}| {{  date("d F Y", strtotime($leaveRequest->leaveEndDate)) }} | {{ $leaveRequest->getStatus() }} |
@endcomponent

@component('mail::button', ['url' => url(Redirect::intended("/viewLeave/$leaveRequest->id")->getTargetUrl())])
View Leave Request Details
@endcomponent

@if ($leaveRequest->leaveStatus == 1)
If you wish to take leave on that particular period, please apply for a new leave by referring to the reason given.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
