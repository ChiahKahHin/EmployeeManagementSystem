@component('mail::message')
Dear {{ ucwords($task->getPersonInCharge->firstname) }} {{ ucwords($task->getPersonInCharge->lastname) }},

<u><b>New Task Details</b></u>

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
