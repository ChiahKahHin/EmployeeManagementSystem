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
				<h2 class="h4 mb-20">Task Added (By month)</h2>
				
				<div style="width: 50%; padding-bottom: 25px;">
					<select class="form-control selectpicker" id="taskAddedYear" name="taskAddedYear" onchange="taskAddedYearChange();" required>						
						@foreach ($years as $year)
							@if ($loop->iteration == 1)
								<option value="{{ $year }}" selected>{{ $year }}</option>
							@else
								<option value="{{ $year }}">{{ $year }}</option>
							@endif
						@endforeach
					</select>
				</div>

				<div id="taskAddedChart"></div>
			</div>
		</div>
	</div>
	{{-- <div class="row">
		<div class="col-xl-12 mb-30">
			<div class="card-box height-100-p pd-20">
				<h2 class="h4 mb-20">Task Approved & Rejected (By month in {{ date('Y') }})</h2>
				<div id="chart5"></div>
			</div>
		</div>
	</div> --}}
	{{-- 
		
			/*series: [{
				name: "Task",
				data: [
					@php
						foreach ($taskAddedArrays as $key => $val){
							echo $val.",";
						}
					@endphp
				]
			}],*/
	--}}
@endsection

@section('script')
	<script>
		$(document).ready(function (){
			taskAddedYearChange();
    	});

		function taskAddedYearChange() {
			var year = document.getElementById('taskAddedYear').value;
			const DATA_URL = "{{ route('taskAddedAnalytics', ':year') }}";
			var url = DATA_URL.replace(":year", year);
			
			$.get(url, function(response) {
				taskAddedChart.updateSeries([{
					name: 'Task',
					data: response
				}])
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
				text: 'Task Added ',
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

		var taskAddedChart = new ApexCharts(document.querySelector("#taskAddedChart"), taskAddedChartOptions);
		taskAddedChart.render();
	</script>
@endsection