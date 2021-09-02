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
            <h2 class="h4 mb-20">Activity</h2>
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
    {{-- <script src="vendors/scripts/dashboard1.js"></script> --}}
    <script>
        var options5 = {
            chart: {
                height: 350,
                type: 'bar',
                parentHeightOffset: 0,
                fontFamily: 'Poppins, sans-serif',
                toolbar: {
                    show: false,
                },
            },
            colors: ['#1b00ff', '#f56767'],
            grid: {
                borderColor: '#c7d2dd',
                strokeDashArray: 5,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '25%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            series: [{
                name: 'In Progress',
                data: [40, 28, 47, 22, 34, 25]
            }, {
                name: 'Complete',
                data: [30, 20, 37, 10, 28, 11]
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                labels: {
                    style: {
                        colors: ['#353535'],
                        fontSize: '16px',
                    },
                },
                axisBorder: {
                    color: '#8fa6bc',
                }
            },
            yaxis: {
                title: {
                    text: ''
                },
                labels: {
                    style: {
                        colors: '#353535',
                        fontSize: '16px',
                    },
                },
                axisBorder: {
                    color: '#f00',
                }
            },
            legend: {
                horizontalAlign: 'right',
                position: 'top',
                fontSize: '16px',
                offsetY: 0,
                labels: {
                    colors: '#353535',
                },
                markers: {
                    width: 10,
                    height: 10,
                    radius: 15,
                },
                itemMargin: {
                    vertical: 0
                },
            },
            fill: {
                opacity: 1

            },
            tooltip: {
                style: {
                    fontSize: '15px',
                    fontFamily: 'Poppins, sans-serif',
                },
                y: {
                    formatter: function (val) {
                        return val
                    }
                }
            }
        }

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