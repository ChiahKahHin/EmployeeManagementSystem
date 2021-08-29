@component('mail::message')
@if ($changeManager)
Dear {{ $leaveRequest->getManager->getFullName() }}/{{ $leaveRequest->getEmployee->getFullName() }},

This leave approval manager is delegate to a new manager:<br> {{ $leaveRequest->getManager->getFullName() }} <br>

@else
@if ($leaveRequest->leaveStatus == 0)
Dear {{ $leaveRequest->getManager->getFullName() }},

A new leave request from {{ $leaveRequest->getEmployee->getFullName() }} is waiting approval. 

@elseif($leaveRequest->leaveStatus == 1)
Dear {{ $leaveRequest->getEmployee->getFullName() }},

Your leave request is rejected.

Reason of leave request rejected: {{ $reason }} 

@elseif($leaveRequest->leaveStatus == 2)
Dear {{ $leaveRequest->getEmployee->getFullName() }},

Your leave request is approved.

@elseif($leaveRequest->leaveStatus == 3)
Dear {{ $leaveRequest->getManager->getFullName() }},

This leave request is cancelled.
@endif
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
