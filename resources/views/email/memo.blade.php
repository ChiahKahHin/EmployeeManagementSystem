@component('mail::message')
# Memo Title
{{ ucwords($memo->memoTitle) }}

# Memo Recipient
@if ($memo->memoRecipient == 0)
All Employees

@else
{{ ucwords($memo->getDepartmentName->departmentName) }} Department

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
