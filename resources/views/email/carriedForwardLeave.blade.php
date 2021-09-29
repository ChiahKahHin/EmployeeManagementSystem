{{-- @component('mail::message')
Dear {{ $carriedForwardLeave->getEmployee->getFullName() }},

Your carried forward leave for {{ date('Y') }} are <<b>{{ $carriedForwardLeave->leaveLimit }} days</b>>.

Please use it before the end of {{ date('Y', strtotime('+1 year')) }}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent --}}

@component('mail::message')
@php
	if($carriedForwardLeave->delegateManagerID == null){
		$managerName = $carriedForwardLeave->getManager->getFullName();
	}
	else{
		$managerName = $carriedForwardLeave->getDelegateManager->getFullName();
	}
@endphp
@if ($carriedForwardLeave->status == 0)
Dear {{ $managerName }},

A new carried forward leave request from {{ $carriedForwardLeave->getEmployee->getFullName() }} is waiting approval. 

@elseif($carriedForwardLeave->status == 1)
Dear {{ $carriedForwardLeave->getEmployee->getFullName() }},

Your carried forward leave request is rejected.

Reason of carried forward leave request rejected: {{ $reason }} 

@elseif($carriedForwardLeave->status == 2)
Dear {{ $carriedForwardLeave->getEmployee->getFullName() }},

Your carried forward leave request is approved.
@endif

<u><b>Leave Request Details</b></u>

Requested Carried Forward Leave: {{ $carriedForwardLeave->leaveLimit }} days

Status: {{ $carriedForwardLeave->getStatus() }} {!! ($carriedForwardLeave->delegateManagerID != null && $carriedForwardLeave->status == 0) ? "<i>(Delegated)</i>" : null !!}

@component('mail::button', ['url' => url(Redirect::intended("/viewCarriedForwardLeave/$carriedForwardLeave->id")->getTargetUrl())])
View Carried Forward Leave Request Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
