@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Benefit Claim Analytics
@endsection

@section('pageTitle')
	Benefit Claim Analytics {{ (Auth::user()->isAccess('hrmanager', 'manager')) ? "(Personal)" : null }}
@endsection

@section('content')
	@if (count($overallClaimYears) == 0 && count($claimApprovedAndRejectedYears) == 0)
		<script>
			swal({
				title: 'Warning',
				html: 'There is no benefit claim analytics available at the moment !',
				type: 'warning',
				confirmButtonClass: 'btn btn-danger',
			}).then(function(){
				window.location.href = "/";
			});
		</script>
	@endif

	@if (count($overallClaimYears) > 0)
		<div class="row">
			<div class="col-xl-12 mb-30">
				<div class="card-box height-100-p pd-20">
					<h2 class="h4 mb-20">Overall Benefit Claim
						@if (Auth::user()->isAccess('hrmanager', 'manager'))
							<a href="{{ route('claimAnalytics2') }}" style="float: right" class="btn btn-outline-primary">
								<i class="icon-copy dw dw-switch"></i> Switch to Manager Analytics
							</a>
						@endif
					</h2>

					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<select class="form-control selectpicker" id="overallClaimYear" name="overallClaimYear" onchange="overallClaimChange();" required>
									@foreach ($overallClaimYears as $overallClaimYear)
										@if ($loop->iteration == 1)
											<option value="{{ $overallClaimYear }}" selected>{{ $overallClaimYear }}</option>
										@else
											<option value="{{ $overallClaimYear }}">{{ $overallClaimYear }}</option>
										@endif
									@endforeach
								</select>
							</div>
						</div>
					</div>

					<div style="width: 25%; padding-bottom: 25px;">
					</div>

					<div id="overallClaim" style="display:flex;" class="pt-4 justify-content-center"></div>
				</div>
			</div>
		</div>
	@endif

	@if (count($claimApprovedAndRejectedYears) > 0)
		<div class="row">
			<div class="col-xl-12 mb-30">
				<div class="card-box height-100-p pd-20">
					<h2 class="h4 mb-20">Benefit Claim Approved & Rejected</h2>

					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<select class="form-control selectpicker" id="claimApprovedAndRejectedYear" name="claimApprovedAndRejectedYear" onchange="claimApprovedAndRejectedChange();" required>
									@foreach ($claimApprovedAndRejectedYears as $claimApprovedAndRejectedYear)
										@if ($loop->iteration == 1)
											<option value="{{ $claimApprovedAndRejectedYear }}" selected>{{ $claimApprovedAndRejectedYear }}</option>
										@else
											<option value="{{ $claimApprovedAndRejectedYear }}">{{ $claimApprovedAndRejectedYear }}</option>
										@endif
									@endforeach
								</select>
							</div>
						</div>
					</div>

					<div id="claimApprovedAndRejected"></div>
				</div>
			</div>
		</div>
	@endif
@endsection

@section('script')
	<script>
		$(document).ready(function (){
			@if (count($overallClaimYears) > 0)
				overallClaimChange();
			@endif
			@if (count($claimApprovedAndRejectedYears) > 0)
				claimApprovedAndRejectedChange();
			@endif
    	});

		function overallClaimChange() {
			var year = document.getElementById('overallClaimYear').value;
			const DATA_URL = "{{ route('overallClaimAnalytics3', [':year']) }}";
			var url = DATA_URL.replace(":year", year);

			console.log(url);
			
			$.get(url, function(response) {
				overallClaimChart.updateOptions({
					title:{
						text: 'Overall Claim in ' + year
					},
					labels: response[0],
					series: response[1]
				})
			});
		}

		function claimApprovedAndRejectedChange() {
			var year = document.getElementById('claimApprovedAndRejectedYear').value;
			const DATA_URL = "{{ route('claimApprovedAndRejectedAnalytics3', [':year']) }}";
			var url = DATA_URL.replace(":year", year);
			
			claimApprovedAndRejectedChart.updateOptions({
				title:{
					text: 'Benefit Claim Approved & Rejected in ' + year
				}
			})
			
			$.get(url, function(response) {
				claimApprovedAndRejectedChart.updateSeries([{
					name: 'Approved',
					data: response[0]
				},{
					name: 'Rejected',
					data: response[1]
				}])
			});
		}

		var overallClaimOptions = {
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

		var claimApprovedAndRejectedOptions = {
			series: [],
			noData:{
				text: 'Loading....'
			},
			title: {
				text: 'Benefit Claim Approved & Rejected By Month in 2021',
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
					text: 'Number of claims'
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

		var overallClaimChart = new ApexCharts(document.querySelector("#overallClaim"), overallClaimOptions);
        overallClaimChart.render();

		var claimApprovedAndRejectedChart = new ApexCharts(document.querySelector("#claimApprovedAndRejected"), claimApprovedAndRejectedOptions);
		claimApprovedAndRejectedChart.render();
	</script>
@endsection