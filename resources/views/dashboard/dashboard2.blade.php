@extends("layouts.template")

@section("title")
    {{ Auth::user()->getRoleName() }} | Dashboard
@endsection

@section("pageTitle")
    Dashboard
@endsection

@section("content")
	
<div class="card-box pd-20 height-100-p mb-30">
    <div class="row align-items-center">
        <div class="col-md-4">
            <img src="vendors/images/banner-img.png" alt="">
        </div>
        <div class="col-md-8">
            <h4 class="font-20 weight-500 mb-10 text-capitalize">
                Welcome back <div class="weight-600 font-30 text-blue">{{ Auth::user()->getFullName() }}!</div>
            </h4>
            <p class="font-18 max-width-600 font-italic text-justify">Quote of the day:<br>{{ $quotes }}</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <h4 class="mb-20 h4">Task Waiting Approval</h4>
            <div class="list-group">
                @forelse ($tasks as $task)
                    <a href="{{ route('viewTask', ['id' => $task->id]) }}" class="list-group-item list-group-item-action">{{ $loop->iteration }}. {{ $task->title }} - {{ $task->getPersonInCharge->getFullName() }} ({{ date("d M", strtotime($task->dueDate)) }})</a>
                @empty
                    <a href="#" class="list-group-item list-group-item-action disabled text-center">No Task is Waiting Approval</a>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <h4 class="mb-20 h4">Leave Waiting Approval</h4>
            <div class="list-group">
                @forelse ($leaves as $leave)
                    <a href="{{ route('viewLeave', ['id' => $leave->id]) }}" class="list-group-item list-group-item-action">{{ $loop->iteration }}. {{ $leave->getLeaveType->leaveType }} - {{ $leave->getEmployee->getFullName() }} ({{ date("d M", strtotime($leave->leaveStartDate)) }} - {{ date("d M", strtotime($leave->leaveEndDate)) }})</a>
                @empty
                    <a href="#" class="list-group-item list-group-item-action disabled text-center">No Leave is Waiting Approval</a>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <h4 class="mb-20 h4">Claim Waiting Approval</h4>
            <div class="list-group">
                @forelse ($claims as $claim)
                    <a href="{{ route('viewClaimRequest', ['id' => $claim->id]) }}" class="list-group-item list-group-item-action">{{ $loop->iteration }}. {{ $claim->getClaimType->claimType }} - {{ $claim->getEmployee->getFullName() }} (RM {{ number_format($claim->claimAmount, 2, '.', '') }})</a>
                @empty
                    <a href="#" class="list-group-item list-group-item-action disabled text-center">No Claim is Waiting Approval</a>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <h4 class="mb-20 h4">Ongoing Training Program</h4>
            <div class="list-group">
                @forelse ($trainingPrograms as $trainingProgram)
                    <a href="{{ route('viewTrainingProgram2', ['id' => $trainingProgram->id]) }}" class="list-group-item list-group-item-action">{{ $loop->iteration }}. {{ $trainingProgram->name }} - {{ date("d F Y, g:ia", strtotime($trainingProgram->dateAndTime)) }}</a>
                @empty
                    <a href="#" class="list-group-item list-group-item-action disabled text-center">No Training Program Available</a>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@section("script")
    <script>

    </script>
@endsection