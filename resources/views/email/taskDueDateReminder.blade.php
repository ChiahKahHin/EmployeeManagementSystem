@component('mail::message')
Dear {{ $task->getPersonInCharge->getFullName() }},

@if ($task->dueDate == date('Y-m-d'))
Your task due date is today, please complete it as soon as possible.
@else
Your task is overdue, please complete it as soon as possible.
@endif

<u><b>Task Details</b></u>

@component('mail::table')
| Title | Description | Priority | Due Date | Status |
|:-----:|:-----------:|:--------:|:--------:|:------:|
| {{ $task->title }} | {{ $task->description }} | {{ $task->priority }} | {{  date("d F Y", strtotime($task->dueDate)) }} | {{ $task->getStatus() }} |
@endcomponent

@component('mail::button', ['url' => url(Redirect::intended("/viewTask/$task->id")->getTargetUrl())])
View Task
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
