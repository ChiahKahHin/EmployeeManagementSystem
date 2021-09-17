@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Training Program Analytics
@endsection

@section('pageTitle')
	Training Program Analytics
@endsection

@section('content')
	@if (count($trainingAddedYears) == 0)
		<script>
			swal({
				title: 'Warning',
				html: 'There is no training program analytics available at the moment !',
				type: 'warning',
				confirmButtonClass: 'btn btn-danger',
			}).then(function(){
				window.location.href = "/";
			});
		</script>
	@endif
	@if (count($trainingAddedYears) > 0)
		<div class="row">
			<div class="col-xl-12 mb-30">
				<div class="card-box height-100-p pd-20">
					<h2 class="h4 mb-20">Training Program Completed</h2>

					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<select class="form-control selectpicker" id="trainingAddedYear" name="trainingAddedYear" onchange="trainingAddedYearChange();" required>
									@foreach ($trainingAddedYears as $trainingAddedYear)
										@if ($loop->iteration == 1)
											<option value="{{ $trainingAddedYear }}" selected>{{ $trainingAddedYear }}</option>
										@else
											<option value="{{ $trainingAddedYear }}">{{ $trainingAddedYear }}</option>
										@endif
									@endforeach
								</select>
							</div>
						</div>
					</div>

					<div id="trainingAdded"></div>
				</div>
			</div>
		</div>
	@endif
@endsection

@section('script')
	<script>
		$(document).ready(function (){
			@if (count($trainingAddedYears) > 0)
				trainingAddedYearChange();
			@endif
    	});

		function trainingAddedYearChange() {
			var year = document.getElementById('trainingAddedYear').value;
			const DATA_URL = "{{ route('trainingAddedAnalytics',':year') }}";
			var url = DATA_URL.replace(":year", year);
			trainingAddedChart.updateOptions({
				title:{
					text: 'Training Program Completed in ' + year
				}
			})
			
			$.get(url, function(response) {
				trainingAddedChart.updateSeries([{
					name: 'Training Program',
					data: response
				}])
			});
		}

		var trainingAddedOptions = {
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

		var trainingAddedChart = new ApexCharts(document.querySelector("#trainingAdded"), trainingAddedOptions);
		trainingAddedChart.render();
	</script>
@endsection