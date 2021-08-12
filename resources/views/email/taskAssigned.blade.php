@component('mail::message')
Dear {{ ucwords($task->getPersonInCharge->firstname) }} {{ ucwords($task->getPersonInCharge->lastname) }},

<u><b>New Task Details</b></u>

@component('mail::table')
| Title | Description | Priority | Due Date | Status |
|:-----:|:-----------:|:--------:|:--------:|:------:|
| {{ $task->title }} | {{ $task->description }} | {{ $task->priority }} | {{  date("d F Y", strtotime($task->dueDate)) }} | {{ $task->getStatus() }} |
@endcomponent

@component('mail::button', ['url' => url('/')])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
