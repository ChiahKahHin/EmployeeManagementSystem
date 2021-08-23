@component('mail::message')
# Memo Title
{{ ucwords($memo->memoTitle) }}

# Memo Recipient
@php
	$recipient = explode(',', $memo->memoRecipient);
@endphp
@if (in_array(0, $recipient))
All Employees

@else
{{ ucwords($memo->getDepartmentName()) }} Department

@endif

# Memo Date
{{ date("d F Y", strtotime($memo->memoDate)) }}

# Memo Description
{!! nl2br($memo->memoDescription) !!}

<hr>
<br><br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
