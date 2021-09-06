@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Task Analytics
@endsection

@section('pageTitle')
	Task Analytics
@endsection

@section('content')
	<div class="row">
		<div class="col-xl-12 mb-30">
			<div class="card-box height-100-p pd-20">
				<h2 class="h4 mb-20">Task Added</h2>
				
				<div style="width: 25%; padding-bottom: 25px;">
					<select class="form-control selectpicker" id="taskAddedYear" name="taskAddedYear" onchange="taskAddedYearChange();" required>						
						@foreach ($taskAddedYears as $taskAddedYear)
							@if ($loop->iteration == 1)
								<option value="{{ $taskAddedYear }}" selected>{{ $taskAddedYear }}</option>
							@else
								<option value="{{ $taskAddedYear }}">{{ $taskAddedYear }}</option>
							@endif
						@endforeach
					</select>
				</div>

				<div id="taskAddedChart"></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-12 mb-30">
			<div class="card-box height-100-p pd-20">
				<h2 class="h4 mb-20">Task Approved & Rejected</h2>

				<div style="width: 25%; padding-bottom: 25px;">
					<select class="form-control selectpicker" id="taskApprovedAndRejectedYear" name="taskApprovedAndRejectedYear" onchange="taskApprovedAndRejectedYearChange();" required>
						@foreach ($taskApprovedAndRejectedYears as $taskApprovedAndRejectedYear)
							@if ($loop->iteration == 1)
								<option value="{{ $taskApprovedAndRejectedYear }}" selected>{{ $taskApprovedAndRejectedYear }}</option>
							@else
								<option value="{{ $taskApprovedAndRejectedYear }}">{{ $taskApprovedAndRejectedYear }}</option>
							@endif
						@endforeach
					</select>
				</div>

				<div id="taskApprovedAndRejected"></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-12 mb-30">
			<div class="card-box height-100-p pd-20">
				<h2 class="h4 mb-20">Overall Task</h2>

				<div style="width: 25%; padding-bottom: 25px;">
					<select class="form-control selectpicker" id="overallTaskYear" name="overTaskYear" onchange="overTaskYearChange();" required>
						@foreach ($taskAddedYears as $taskAddedYear)
							@if ($loop->iteration == 1)
								<option value="{{ $taskAddedYear }}" selected>{{ $taskAddedYear }}</option>
							@else
								<option value="{{ $taskAddedYear }}">{{ $taskAddedYear }}</option>
							@endif
						@endforeach
					</select>
				</div>

				<div id="overallTask" style="display:flex;" class="pt-4 justify-content-center"></div>
			</div>
		</div>
	</div>
@endsection

@section('script')
	<script>
		$(document).ready(function (){
			taskAddedYearChange();
			taskApprovedAndRejectedYearChange();
			overTaskYearChange();
    	});

		function taskAddedYearChange() {
			var year = document.getElementById('taskAddedYear').value;
			const DATA_URL = "{{ route('taskAddedAnalytics', ':year') }}";
			var url = DATA_URL.replace(":year", year);
			taskAddedChart.updateOptions({
				title:{
					text: 'Task Added (By Month) in ' + year
				}
			})
			
			$.get(url, function(response) {
				taskAddedChart.updateSeries([{
					name: 'Task',
					data: response
				}])
			});
		}

		function taskApprovedAndRejectedYearChange() {
			var year = document.getElementById('taskApprovedAndRejectedYear').value;
			const DATA_URL = "{{ route('taskApprovedAndRejectedAnalytics', ':year') }}";
			var url = DATA_URL.replace(":year", year);
			taskApprovedAndRejectedChart.updateOptions({
				title:{
					text: 'Task Approved & Rejected (By Month) in ' + year
				}
			})
			
			$.get(url, function(response) {
				taskApprovedAndRejectedChart.updateSeries([{
					name: 'Approved',
					data: response[0]
				},{
					name: 'Rejected',
					data: response[1]
				}])
			});
		}

		function overTaskYearChange() {
			var year = document.getElementById('overallTaskYear').value;
			const DATA_URL = "{{ route('overallTaskAnalytics', ':year') }}";
			var url = DATA_URL.replace(":year", year);
			
			$.get(url, function(response) {
				overallTaskChart.updateOptions({
					title:{
						text: 'Overall Task in ' + year
					},
					labels: response[0],
					series: response[1]
				})
			});
		}

		var taskAddedChartOptions = {
			series:[],
			noData:{
				text: 'Loading....'
			},
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
				text: '',
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
			},
			yaxis: {
				title: {
					text: 'Number of tasks'
				}
			}
		};

		var taskApprovedAndRejectedOptions = {
			title:{
				text: 'Task Approved & Rejected',
				align: 'left'
			},
			chart: {
				height: 350,
				type: 'bar',
				parentHeightOffset: 0,
				fontFamily: 'Poppins, sans-serif',
				toolbar: {
					show: true,
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
			series: [],
			xaxis: {
				categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
				labels: {
					style: {
						colors: ['#353535'],
						fontSize: '16px',
					},
				},
				axisBorder: {
					color: '#8fa6bc',
				},
				title: {
					text: 'Month'
				}
			},
			yaxis: {
				title: {
					text: 'Number of tasks'
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
					//horizontal: 20,
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

		var overallTaskOptions = {
			series:[],
            chart: {
                width: 550,
                type: 'pie',
				toolbar: {
					show: true,
					offsetX: 0,
					offsetY: 0,
					tools: {
						download: true,
						selection: true,
						zoom: true,
						zoomin: true,
						zoomout: true,
						pan: true,
						customIcons: []
					},
					export: {
						csv: {
							filename: undefined,
							columnDelimiter: ',',
							headerCategory: 'category',
							headerValue: 'value',
							dateFormatter(timestamp) {
							return new Date(timestamp).toDateString()
							}
						},
						svg: {
							filename: undefined,
						},
						png: {
							filename: undefined,
						}
					},
					autoSelected: 'zoom' 
				},
            },
            labels: [],
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

		var taskAddedChart = new ApexCharts(document.querySelector("#taskAddedChart"), taskAddedChartOptions);
		taskAddedChart.render();

		var taskApprovedAndRejectedChart = new ApexCharts(document.querySelector("#taskApprovedAndRejected"), taskApprovedAndRejectedOptions);
		taskApprovedAndRejectedChart.render();

		var overallTaskChart = new ApexCharts(document.querySelector("#overallTask"), overallTaskOptions);
        overallTaskChart.render();
	</script>
@endsection