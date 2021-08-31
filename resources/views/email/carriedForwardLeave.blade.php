@component('mail::message')
Dear {{ $carriedForwardLeave->getEmployee->getFullName() }},

Your carried forward leave for {{ date('Y') }} are <<b>{{ $carriedForwardLeave->leaveLimit }} days</b>>.

Please use it before the end of {{ date('Y', strtotime('+1 year')) }}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
