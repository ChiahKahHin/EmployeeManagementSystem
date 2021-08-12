@component('mail::message')
Dear {{ ucwords($task->getPersonInCharge->firstname) }} {{ ucwords($task->getPersonInCharge->lastname) }},

@if ($task->status == 2)
Your task is rejected by the manager. <br><br>
Reason of task rejected: {{ $reason }} <br>
@endif

<u><b>Task Details</b></u>

@component('mail::table')
| Title | Description | Priority | Due Date | Status |
|:-----:|:-----------:|:--------:|:--------:|:------:|
| {{ $task->title }} | {{ $task->description }} | {{ $task->priority }} | {{  date("d F Y", strtotime($task->dueDate)) }} | {{ $task->getStatus() }} |
@endcomponent

@component('mail::button', ['url' => url('/')])
Login
@endcomponent

@if ($task->status == 2)
Please complete it, and request for approval again.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
