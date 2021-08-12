@component('mail::message')
Dear {{ ucwords($employee->firstname) }} {{ ucwords($employee->lastname) }},

<u><b>New Task Details</b></u>

@component('mail::table')
| Title | Description | Priority | Due Date | Status |
|:-----:|:-----------:|:--------:|:--------:|:------:|
| {{ $task->title }} | {{ $task->description }} | {{ $task->priority }} | {{  date("d F Y", strtotime($task->dueDate)) }} | Pending |
@endcomponent

@component('mail::button', ['url' => url('/')])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
