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
							<select class="form-control selectpicker" id="overallTaskPic" name="overallTaskPic" onchange="overallTaskChange();" required>
								<option value="" data-picName="All Person In Charge" selected>All Person In Charge</option>
								@php
									$picArray = [];
								@endphp
								@foreach ($personInCharges as $personInCharge)
									@if(!in_array($personInCharge->getPersonInCharge->id, $picArray))
										<option value="{{ $personInCharge->getPersonInCharge->id }}" data-picName="{{ $personInCharge->getPersonInCharge->getFullName() }}">{{ $personInCharge->getPersonInCharge->getFullName() }}</option>
										@php
											$picArray[] = $personInCharge->getPersonInCharge->id;
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
							<select class="form-control selectpicker" id="taskCompletedPic" name="taskCompletedPic" onchange="taskCompletedChange();" required>
								<option value="" data-picName="All Person In Charge" selected>All Person In Charge</option>
								@php
									$picArray = [];
								@endphp
								@foreach ($personInCharges as $personInCharge)
									@if(!in_array($personInCharge->getPersonInCharge->id, $picArray))
										<option value="{{ $personInCharge->getPersonInCharge->id }}" data-picName="{{ $personInCharge->getPersonInCharge->getFullName() }}">{{ $personInCharge->getPersonInCharge->getFullName() }}</option>
										@php
											$picArray[] = $personInCharge->getPersonInCharge->id;
										@endphp
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
							<select class="form-control selectpicker" id="taskApprovedAndRejectedPic" name="taskApprovedAndRejectedPic" onchange="taskApprovedAndRejectedChange();" required>
								<option value="" data-picName="All Person In Charge" selected>All Person In Charge</option>
								@php
									$picArray = [];
								@endphp
								@foreach ($personInCharges as $personInCharge)
									@if(!in_array($personInCharge->getPersonInCharge->id, $picArray))
										<option value="{{ $personInCharge->getPersonInCharge->id }}" data-picName="{{ $personInCharge->getPersonInCharge->getFullName() }}">{{ $personInCharge->getPersonInCharge->getFullName() }}</option>
										@php
											$picArray[] = $personInCharge->getPersonInCharge->id;
										@endphp
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
			var personInCharge = document.getElementById('overallTaskPic');
			var picID = personInCharge.value;
			var picName = personInCharge.options[personInCharge.selectedIndex].getAttribute('data-picName');
			if(picID == ""){
				picID = null;
			}
			const DATA_URL = "{{ route('overallTaskAnalytics2', [':year', ':picID']) }}";
			var url = DATA_URL.replace(":year", year);
			url = url.replace(":picID", picID);

			console.log(url);
			console.log(picName);
			
			$.get(url, function(response) {
				overallTaskChart.updateOptions({
					title:{
						text: 'Overall Task in ' + year + " (" + picName +")"
					},
					labels: response[0],
					series: response[1]
				})
			});
		}

		function taskCompletedChange() {
			var year = document.getElementById('taskCompletedYear').value;
			var personInCharge = document.getElementById('taskCompletedPic');
			var picID = personInCharge.value;
			var picName = personInCharge.options[personInCharge.selectedIndex].getAttribute('data-picName');
			if(picID == ""){
				picID = null;
			}
			const DATA_URL = "{{ route('taskCompletedAnalytics2',[':year',':picID']) }}";
			var url = DATA_URL.replace(":year", year);
			url = url.replace(":picID", picID);

			taskCompletedChart.updateOptions({
				title:{
					text: 'Task Completed Before & After Due Date in ' + year + ' (' + picName + ')'
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
			var personInCharge = document.getElementById('taskApprovedAndRejectedPic');
			var picID = personInCharge.value;
			var picName = personInCharge.options[personInCharge.selectedIndex].getAttribute('data-picName');
			if(picID == ""){
				picID = null;
			}
			const DATA_URL = "{{ route('taskApprovedAndRejectedAnalytics2',[':year',':picID']) }}";
			var url = DATA_URL.replace(":year", year);
			url = url.replace(":picID", picID);
			taskApprovedAndRejectedChart.updateOptions({
				title:{
					text: 'Task Approved & Rejected in ' + year + ' (' + picName + ')'
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