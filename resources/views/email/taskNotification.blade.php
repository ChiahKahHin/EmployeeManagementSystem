@component('mail::message')
@if ($task->status == 1)
@if($task->delegateManagerID == null)
Dear {{ $task->getManager->getFullName() }},
@else
Dear {{ $task->getDelegateManager->getFullName() }},
@endif
@else

Dear {{ $task->getPersonInCharge->getFullName() }},

@endif

@if ($task->status == 2)

Your task is rejected. <br>
Reason of task rejected: {{ $reason }} <br>

@elseif ($task->status == 3)

Your task is approved. <br>

@elseif ($task->status == 1)

A task is waiting for approval. <br>

@endif

	
@if ($task->status == 0)
<u><b>New Task Details</b></u>
	
@else
<u><b>Task Details</b></u>

@endif

@component('mail::table')
| Title | Priority | Due Date | Status |
|:-----:|:--------:|:--------:|:------:|
| {{ $task->title }} | {{ $task->priority }} | {{  date("d F Y", strtotime($task->dueDate)) }} | {{ $task->getStatus() }} {!! ($task->delegateManagerID != null && $task->status == 1) ? "<i>(Delegated)</i>" : null !!} |
@endcomponent

<u>Task Description</u>

{{ $task->description }}

@component('mail::button', ['url' => url(Redirect::intended("/viewTask/$task->id")->getTargetUrl())])
View Task
@endcomponent

@if ($task->status == 2)
Please complete it, and request for approval again.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
