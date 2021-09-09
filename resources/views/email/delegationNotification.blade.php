@component('mail::message')
Dear {{ $delegation->getDelegateManager->getFullName() }},

@if ($delegation->status == 0)
A new approval delegation is added. <br>
Reason of delegation: {{ $delegation->reason }}

@elseif ($delegation->status == 1)
An approval delegation is ongoing. <br>
Reason of delegation: {{ $delegation->reason }}

@elseif ($delegation->status == 3)
Approval delegation was cancelled by the existing manager.
@endif

@component('mail::table')
| Existing Manager | Start Date | End Date | Status |
|:----------------:|:----------:|:--------:|:------:|
| {{ $delegation->getManager->getFullName() }} | {{  date("d F Y", strtotime($delegation->startDate)) }} | {{  date("d F Y", strtotime($delegation->endDate)) }} | {{ $delegation->getStatus() }} |
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
