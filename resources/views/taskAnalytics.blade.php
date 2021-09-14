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
				<h2 class="h4 mb-20">Overall Task</h2>

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
							<div class="col-md-6">
								<select class="form-control selectpicker" id="overallTaskDepartment" name="overTaskDepartment" onchange="overallTaskChange();" required>
									<option value="" data-departmentName="All Departments" selected>All Departments</option>
									@php
										$departmentArray = [];
									@endphp
									@foreach ($departments as $department)
										@if(!in_array($department->getPersonInCharge->getDepartment->id, $departmentArray))
											<option value="{{ $department->getPersonInCharge->getDepartment->id }}" data-departmentName="{{ $department->getPersonInCharge->getDepartment->departmentName }}">{{ $department->getPersonInCharge->getDepartment->departmentName }}</option>
											@php
												$departmentArray[] = $department->getPersonInCharge->getDepartment->id;
											@endphp
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
						<div class="col-md-6">
							<select class="form-control selectpicker" id="taskCompletedDepartment" name="taskCompletedDepartment" onchange="taskCompletedChange();" required>
								<option value="" data-departmentName="All Departments" selected>All Departments</option>
								@php
									$departmentArray = [];
								@endphp
								@foreach ($departments as $department)
									@if ($department->status == 3)
										@if(!in_array($department->getPersonInCharge->getDepartment->id, $departmentArray))
											<option value="{{ $department->getPersonInCharge->getDepartment->id }}" data-departmentName="{{ $department->getPersonInCharge->getDepartment->departmentName }}">{{ $department->getPersonInCharge->getDepartment->departmentName }}</option>
											@php
												$departmentArray[] = $department->getPersonInCharge->getDepartment->id;
											@endphp
										@endif
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
						<div class="col-md-6">
							<select class="form-control selectpicker" id="taskApprovedAndRejectedDepartment" name="taskApprovedAndRejectedDepartment" onchange="taskApprovedAndRejectedChange();" required>
								<option value="" data-departmentName="All Departments" selected>All Departments</option>
								@php
									$departmentArray = [];
								@endphp
								@foreach ($departments as $department)
									@if ($department->status == 2 || $department->status == 3)
										@if(!in_array($department->getPersonInCharge->getDepartment->id, $departmentArray))
											<option value="{{ $department->getPersonInCharge->getDepartment->id }}" data-departmentName="{{ $department->getPersonInCharge->getDepartment->departmentName }}">{{ $department->getPersonInCharge->getDepartment->departmentName }}</option>
											@php
												$departmentArray[] = $department->getPersonInCharge->getDepartment->id;
											@endphp
										@endif
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
@endsection

@section('script')
	<script>
		$(document).ready(function (){
			overallTaskChange();
			taskCompletedChange();
			taskApprovedAndRejectedChange();
    	});

		function overallTaskChange() {
			var year = document.getElementById('overallTaskYear').value;
			var department = document.getElementById('overallTaskDepartment');
			var departmentID = department.value;
			var departmentName = department.options[department.selectedIndex].getAttribute('data-departmentName');
			if(departmentID == ""){
				departmentID = null;
			}
			const DATA_URL = "{{ route('overallTaskAnalytics', [':year', ':departmentID']) }}";
			var url = DATA_URL.replace(":year", year);
			url = url.replace(":departmentID", departmentID);

			console.log(url);
			console.log(departmentName);
			
			$.get(url, function(response) {
				overallTaskChart.updateOptions({
					title:{
						text: 'Overall Task in ' + year + " (" + departmentName +")"
					},
					labels: response[0],
					series: response[1]
				})
			});
		}

		function taskCompletedChange() {
			// var year = document.getElementById('taskAddedYear').value;
			// const DATA_URL = "{{-- route('taskCompletedAnalytics',':year') --}}";
			// var url = DATA_URL.replace(":year", year);
			// taskAddedChart.updateOptions({
			// 	title:{
			// 		text: 'Task Added (By Month) in ' + year
			// 	}
			// })
			
			// $.get(url, function(response) {
			// 	taskAddedChart.updateSeries([{
			// 		name: 'Task',
			// 		data: response
			// 	}])
			// });

			var year = document.getElementById('taskCompletedYear').value;
			var department = document.getElementById('taskCompletedDepartment');
			var departmentID = department.value;
			var departmentName = department.options[department.selectedIndex].getAttribute('data-departmentName');
			if(departmentID == ""){
				departmentID = null;
			}
			const DATA_URL = "{{ route('taskCompletedAnalytics', [':year', ':departmentID']) }}";
			var url = DATA_URL.replace(":year", year);
			url = url.replace(":departmentID", departmentID);

			taskCompletedChart.updateOptions({
				title:{
					text: 'Task Completed Before & After Due Date in ' + year + ' (' + departmentName + ')'
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
			var department = document.getElementById('taskApprovedAndRejectedDepartment');
			var departmentID = department.value;
			var departmentName = department.options[department.selectedIndex].getAttribute('data-departmentName');
			if(departmentID == ""){
				departmentID = null;
			}
			const DATA_URL = "{{ route('taskApprovedAndRejectedAnalytics', [':year', ':departmentID']) }}";
			var url = DATA_URL.replace(":year", year);
			url = url.replace(":departmentID", departmentID);
			taskApprovedAndRejectedChart.updateOptions({
				title:{
					text: 'Task Approved & Rejected in ' + year + ' (' + departmentName + ')'
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
			// series:[],
			// noData:{
			// 	text: 'Loading....'
			// },
			// chart: {
			// 	height: 350,
			// 	type: 'line',
			// 	zoom: {
			// 		enabled: false
			// 	}
			// },
			// dataLabels: {
			// 	enabled: false
			// },
			// stroke: {
			// 	curve: 'straight'
			// },
			// title: {
			// 	text: '',
			// 	align: 'left'
			// },
			// grid: {
			// 	row: {
			// 		colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
			// 		opacity: 0.5
			// 	},
			// },
			// xaxis: {
			// 	type: 'category',
			// 	categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			// 	title: {
			// 		text: 'Month'
			// 	}
			// },
			// yaxis: {
			// 	title: {
			// 		text: 'Number of tasks'
			// 	}
			// }
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
			// title:{
			// 	text: 'Task Approved & Rejected',
			// 	align: 'left'
			// },
			// chart: {
			// 	height: 350,
			// 	type: 'bar',
			// 	parentHeightOffset: 0,
			// 	fontFamily: 'Poppins, sans-serif',
			// 	toolbar: {
			// 		show: true,
			// 	},
			// },
			// colors: ['#1b00ff', '#f56767'],
			// grid: {
			// 	borderColor: '#c7d2dd',
			// 	strokeDashArray: 5,
			// },
			// plotOptions: {
			// 	bar: {
			// 		horizontal: false,
			// 		columnWidth: '25%',
			// 		endingShape: 'rounded'
			// 	},
			// },
			// dataLabels: {
			// 	enabled: false
			// },
			// stroke: {
			// 	show: true,
			// 	width: 2,
			// 	colors: ['transparent']
			// },
			// series: [],
			// xaxis: {
			// 	categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			// 	labels: {
			// 		style: {
			// 			colors: ['#353535'],
			// 			fontSize: '16px',
			// 		},
			// 	},
			// 	axisBorder: {
			// 		color: '#8fa6bc',
			// 	},
			// 	title: {
			// 		text: 'Month'
			// 	}
			// },
			// yaxis: {
			// 	title: {
			// 		text: 'Number of tasks'
			// 	},
			// 	labels: {
			// 		style: {
			// 			colors: '#353535',
			// 			fontSize: '16px',
			// 		},
			// 	},
			// 	axisBorder: {
			// 		color: '#f00',
			// 	}
			// },
			// legend: {
			// 	horizontalAlign: 'right',
			// 	position: 'top',
			// 	fontSize: '16px',
			// 	offsetY: 0,
			// 	labels: {
			// 		colors: '#353535',
			// 	},
			// 	markers: {
			// 		width: 10,
			// 		height: 10,
			// 		radius: 15,
			// 	},
			// 	itemMargin: {
			// 		//horizontal: 20,
			// 		vertical: 0
			// 	},
			// },
			// fill: {
			// 	opacity: 1

			// },
			// tooltip: {
			// 	style: {
			// 		fontSize: '15px',
			// 		fontFamily: 'Poppins, sans-serif',
			// 	},
			// 	y: {
			// 		formatter: function (val) {
			// 			return val
			// 		}
			// 	}
			// }
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