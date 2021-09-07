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
                Welcome back <div class="weight-600 font-30 text-blue">{{ Auth::user()->getFullName() }} !</div>
            </h4>
            <p class="font-18 max-width-600 font-italic text-justify">Quote of the day:<br>{{ $quotes }}</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-7 mb-30">
        <div class="card-box height-100-p pd-20">
            <h2 class="h4 mb-20">New Account Created (By month in {{ date('Y') }})</h2>
            <div id="chart5"></div>
        </div>
    </div>
    <div class="col-xl-5 mb-30">
        <div class="card-box height-100-p pd-20">
            <h2 class="h4 mb-20">Department <i>(Number of employees)</i></h2>
            <div id="chart6" style="display:flex;" class="pt-4 justify-content-center"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <h4 class="mb-20 h4">Scheduled Memo</h4>
            <div class="list-group">
                @forelse ($memos as $memo)
                    <a href="{{ route('viewMemo', ['id' => $memo->id]) }}" class="list-group-item list-group-item-action">{{ $loop->iteration }}. {{ $memo->memoTitle }} - {{ date("d F Y", strtotime($memo->memoDate)) }}</a>
                @empty
                    <a href="#" class="list-group-item list-group-item-action disabled text-center">No Scheduled Memo</a>
                @endforelse
            </div>
        </div>
    </div>

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
</div>

<div class="row">
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
</div>

@endsection

@section("script")
    <script>
        var options5 = {
            series: [{
                name: "Account",
                data: [
                    @php
                        foreach ($accountArrays as $key => $val){
                            echo $val.",";
                        }
                    @endphp    
                    
                ]
            }],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            title: {
                text: 'Account created ',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                type: 'category',
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                title: {
                    text: 'Month'
                }
            }
        };

        var options6 = {
            series:[
                @php
                    for ($i = 0; $i < count($employeeNumber); $i++) {
                        echo $employeeNumber[$i].",";
                    }
                @endphp
            ],
            chart: {
                width: 450,
                type: 'pie',
            },
            labels: [
                @php
                    for ($i = 0; $i < count($departmentName); $i++) {
                        echo "'".$departmentName[$i]."',";
                    }
                @endphp
            ],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart5 = new ApexCharts(document.querySelector("#chart5"), options5);
        chart5.render();

        var chart6 = new ApexCharts(document.querySelector("#chart6"), options6);
        chart6.render();
    </script>
@endsection