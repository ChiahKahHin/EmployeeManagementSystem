@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Training Program Analytics
@endsection

@section('pageTitle')
	Training Program Analytics
@endsection

@section('content')
	<div class="row">
		<div class="col-xl-12 mb-30">
			<div class="card-box height-100-p pd-20">
				<h2 class="h4 mb-20">Training Program Registered</h2>

				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<select class="form-control selectpicker" id="trainingRegisteredYear" name="trainingRegisteredYear" onchange="trainingRegisteredYearChange();" required>
								@foreach ($trainingRegisteredYears as $trainingRegisteredYear)
									@if ($loop->iteration == 1)
										<option value="{{ $trainingRegisteredYear }}" selected>{{ $trainingRegisteredYear }}</option>
									@else
										<option value="{{ $trainingRegisteredYear }}">{{ $trainingRegisteredYear }}</option>
									@endif
								@endforeach
							</select>
						</div>
					</div>
				</div>

				<div id="trainingRegistered"></div>
			</div>
		</div>
	</div>
@endsection

@section('script')
	<script>
		$(document).ready(function (){
			trainingRegisteredYearChange();
    	});

		function trainingRegisteredYearChange() {
			var year = document.getElementById('trainingRegisteredYear').value;
			const DATA_URL = "{{ route('trainingRegisteredAnalytics',':year') }}";
			var url = DATA_URL.replace(":year", year);
			trainingRegisteredChart.updateOptions({
				title:{
					text: 'Training Program Registered in ' + year
				}
			})
			
			$.get(url, function(response) {
				trainingRegisteredChart.updateSeries([{
					name: 'Training Program',
					data: response
				}])
			});
		}

		var trainingRegisteredOptions = {
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
					text: 'Number of training program'
				}
			}
		}

		var trainingRegisteredChart = new ApexCharts(document.querySelector("#trainingRegistered"), trainingRegisteredOptions);
		trainingRegisteredChart.render();
	</script>
@endsection