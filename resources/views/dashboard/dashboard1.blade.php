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
            <p class="font-18 max-width-600 font-italic text-justify">Quote of the day:<br>{{ $quotes[rand(0, (count($quotes) - 1))] }}</p>
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
                text: 'Account created by month',
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