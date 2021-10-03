@component('mail::message')
Dear {{ $carriedForwardLeave->getEmployee->getFullName() }},
@if ($carriedForwardLeave->leaveLimit != 0)

Your carried forward leave for {{ date('Y') }} are <<b>{{ $carriedForwardLeave->leaveLimit }} days</b>>.

Please use it before {{ date("d F Y", strtotime($carriedForwardLeave->useBefore)) }}.
	
@else

Your carried forward leave request had been removed from the system, because all the annual leave had been applied.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent