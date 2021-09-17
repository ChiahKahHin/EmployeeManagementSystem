@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Task Analytics
@endsection

@section('pageTitle')
	Task Analytics {{ (Auth::user()->isAccess('hrmanager', 'manager')) ? "(Personal)" : null }}
@endsection

@section('content')
	@if (count($overallTaskYears) == 0 && count($taskCompletedYears) == 0 && count($taskApprovedAndRejectedYears) == 0)
		<script>
			swal({
				title: 'Warning',
				html: 'There is no task analytics available at the moment !',
				type: 'warning',
				confirmButtonClass: 'btn btn-danger',
			}).then(function(){
				window.location.href = "/";
			});
		</script>
	@endif

	@if (count($overallTaskYears) > 0)
		<div class="row">
			<div class="col-xl-12 mb-30">
				<div class="card-box height-100-p pd-20">
					<h2 class="h4 mb-20">Overall Task
						@if (Auth::user()->isAccess('hrmanager', 'manager'))
							<a href="{{ route('taskAnalyticsPage2') }}" style="float: right" class="btn btn-outline-primary">
								<i class="icon-copy dw dw-switch"></i> Switch to Manager Analytics
							</a>
						@endif
					</h2>

					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<select class="form-control selectpicker" id="overallTaskYear" name="overTaskYear" onchange="overallTaskChange();" required>
									@foreach ($overallTaskYears as $overallTaskYear)
										@if ($loop->iteration == 1)
											<option value="{{ $overallTaskYear }}" selected>{{ $overallTaskYear }}</option>
										@else
											<option value="{{ $overallTaskYear }}">{{ $overallTaskYear }}</option>
										@endif
									@endforeach
								</select>
							</div>
						</div>
					</div>

					<div style="width: 25%; padding-bottom: 25px;">
					</div>

					<div id="overallTask" style="display:flex;" class="pt-4 justify-content-center"></div>
				</div>
			</div>
		</div>
	@endif

	@if (count($taskCompletedYears) > 0)
		<div class="row">
			<div class="col-xl-12 mb-30">
				<div class="card-box height-100-p pd-20">
					<h2 class="h4 mb-20">Task Completed Before & After Due Date</h2>
					
					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<select class="form-control selectpicker" id="taskCompletedYear" name="taskCompletedYear" onchange="taskCompletedChange();" required>						
									@foreach ($taskCompletedYears as $taskCompletedYear)
										@if ($loop->iteration == 1)
											<option value="{{ $taskCompletedYear }}" selected>{{ $taskCompletedYear }}</option>
										@else
											<option value="{{ $taskCompletedYear }}">{{ $taskCompletedYear }}</option>
										@endif
									@endforeach
								</select>
							</div>
						</div>
					</div>

					<div id="taskCompletedChart"></div>
				</div>
			</div>
		</div>
	@endif

	@if (count($taskApprovedAndRejectedYears) > 0)
		<div class="row">
			<div class="col-xl-12 mb-30">
				<div class="card-box height-100-p pd-20">
					<h2 class="h4 mb-20">Task Approved & Rejected</h2>

					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<select class="form-control selectpicker" id="taskApprovedAndRejectedYear" name="taskApprovedAndRejectedYear" onchange="taskApprovedAndRejectedChange();" required>
									@foreach ($taskApprovedAndRejectedYears as $taskApprovedAndRejectedYear)
										@if ($loop->iteration == 1)
											<option value="{{ $taskApprovedAndRejectedYear }}" selected>{{ $taskApprovedAndRejectedYear }}</option>
										@else
											<option value="{{ $taskApprovedAndRejectedYear }}">{{ $taskApprovedAndRejectedYear }}</option>
										@endif
									@endforeach
								</select>
							</div>
						</div>
					</div>

					<div id="taskApprovedAndRejected"></div>
				</div>
			</div>
		</div>
	@endif
@endsection

@section('script')
	<script>
		$(document).ready(function (){
			@if (count($overallTaskYears) > 0)
				overallTaskChange();
			@endif
			@if (count($taskCompletedYears) > 0)
				taskCompletedChange();
			@endif
			@if (count($taskApprovedAndRejectedYears) > 0)
				taskApprovedAndRejectedChange();
			@endif
    	});

		function overallTaskChange() {
			var year = document.getElementById('overallTaskYear').value;
			const DATA_URL = "{{ route('overallTaskAnalytics3', [':year']) }}";
			var url = DATA_URL.replace(":year", year);

			console.log(url);
			
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

		function taskCompletedChange() {
			var year = document.getElementById('taskCompletedYear').value;
			const DATA_URL = "{{ route('taskCompletedAnalytics3',[':year']) }}";
			var url = DATA_URL.replace(":year", year);

			taskCompletedChart.updateOptions({
				title:{
					text: 'Task Completed Before & After Due Date in ' + year
				}
			})
			
			$.get(url, function(response) {
				taskCompletedChart.updateSeries([{
					name: 'Completed Before Due Date',
					data: response[0]
				},{
					name: 'Completed After Due Date',
					data: response[1]
				}])
			});
		}

		function taskApprovedAndRejectedChange() {
			var year = document.getElementById('taskApprovedAndRejectedYear').value;
			const DATA_URL = "{{ route('taskApprovedAndRejectedAnalytics3',[':year']) }}";
			var url = DATA_URL.replace(":year", year);
			
			taskApprovedAndRejectedChart.updateOptions({
				title:{
					text: 'Task Approved & Rejected in ' + year
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

		var taskCompletedChartOptions = {
			series: [],
			noData:{
				text: 'Loading....'
			},
			title: {
				text: 'Task Completed (Before & After Due Date)',
				align: 'left'
			},
			chart: {
				height: 350,
				type: 'line',
				dropShadow: {
					enabled: true,
					color: '#000',
					top: 18,
					left: 7,
					blur: 10,
					opacity: 0.2
				},
				toolbar: {
					show: true,
					offsetX: 0,
					offsetY: 0,
					tools: {
						download: true,
						selection: false,
						zoom: false,
						zoomin: false,
						zoomout: false,
						pan: false,
						reset: false,
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
			colors: ['#18D800', '#FF1F00'],
			dataLabels: {
				enabled: true,
			},
			stroke: {
				curve: 'smooth'
			},
			grid: {
				borderColor: '#e7e7e7',
				row: {
					colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
					opacity: 0.5
				},
			},
			markers: {
				size: 1
			},
			xaxis: {
				categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
				title: {
					text: 'Month'
				}
			},
			yaxis: {
				title: {
					text: 'Number of tasks'
				},
			},
			legend: {
				position: 'top',
				horizontalAlign: 'right',
				floating: true,
				offsetY: -10,
				offsetX: -5
			}
		};

		var taskApprovedAndRejectedOptions = {
			series: [],
			noData:{
				text: 'Loading....'
			},
			title: {
				text: 'Task Approved & Rejected (By Month) in 2021',
				align: 'left'
			},
			chart: {
				height: 350,
				type: 'line',
				dropShadow: {
					enabled: true,
					color: '#000',
					top: 18,
					left: 7,
					blur: 10,
					opacity: 0.2
				},
				toolbar: {
					show: true,
					offsetX: 0,
					offsetY: 0,
					tools: {
						download: true,
						selection: false,
						zoom: false,
						zoomin: false,
						zoomout: false,
						pan: false,
						reset: false,
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
			colors: ['#18D800', '#FF1F00'],
			dataLabels: {
				enabled: true,
			},
			stroke: {
				curve: 'smooth'
			},
			grid: {
				borderColor: '#e7e7e7',
				row: {
					colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
					opacity: 0.5
				},
			},
			markers: {
				size: 1
			},
			xaxis: {
				categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
				title: {
					text: 'Month'
				}
			},
			yaxis: {
				title: {
					text: 'Number of tasks'
				},
			},
			legend: {
				position: 'top',
				horizontalAlign: 'right',
				floating: true,
				offsetY: -10,
				offsetX: -5
			}
		}

		var taskCompletedChart = new ApexCharts(document.querySelector("#taskCompletedChart"), taskCompletedChartOptions);
		taskCompletedChart.render();

		var taskApprovedAndRejectedChart = new ApexCharts(document.querySelector("#taskApprovedAndRejected"), taskApprovedAndRejectedOptions);
		taskApprovedAndRejectedChart.render();

		var overallTaskChart = new ApexCharts(document.querySelector("#overallTask"), overallTaskOptions);
        overallTaskChart.render();
	</script>
@endsection