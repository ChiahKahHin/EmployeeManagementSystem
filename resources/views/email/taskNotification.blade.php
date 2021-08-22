@component('mail::message')
@if ($task->status != 1)
Dear {{ $task->getPersonInCharge->getFullName() }},
	
@else
Dear {{ $task->getManager->getFullName() }},

@endif

@if ($task->status == 2)

Your task is rejected by the manager. <br>
Reason of task rejected: {{ $reason }} <br>

@elseif ($task->status == 3)

Your task is approved by the manager. <br>

@elseif ($task->status == 1)

A task is waiting for approval. <br>
@endif

@if ($task->status == 0)

<u><b>New Task Details</b></u>
	
@else

<u><b>Task Details</b></u>

@endif

@component('mail::table')
| Title | Description | Priority | Due Date | Status |
|:-----:|:-----------:|:--------:|:--------:|:------:|
| {{ $task->title }} | {{ $task->description }} | {{ $task->priority }} | {{  date("d F Y", strtotime($task->dueDate)) }} | {{ $task->getStatus() }} |
@endcomponent

@component('mail::button', ['url' => url(Redirect::intended("/viewTask/$task->id")->getTargetUrl())])
View Task
@endcomponent

@if ($task->status == 2)
Please complete it, and request for approval again.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
