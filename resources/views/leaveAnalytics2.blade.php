@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Leave Analytics
@endsection

@section('pageTitle')
	Leave Analytics
@endsection

@section('content')
	@if (count($overallLeaveYears) == 0 && count($leaveApprovedAndRejectedYears) == 0)
		<script>
			swal({
				title: 'Warning',
				html: 'There is no leave analytics available at the moment !',
				type: 'warning',
				confirmButtonClass: 'btn btn-danger',
			}).then(function(){
				window.location.href = "/";
			});
		</script>
	@endif
	@if (count($overallLeaveYears) > 0)
		<div class="row">
			<div class="col-xl-12 mb-30">
				<div class="card-box height-100-p pd-20">
					<h2 class="h4 mb-20">Overall Leave</h2>

					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<select class="form-control selectpicker" id="overallLeaveYear" name="overallLeaveYear" onchange="overallLeaveChange();" required>
									@foreach ($overallLeaveYears as $overallLeaveYear)
										@if ($loop->iteration == 1)
											<option value="{{ $overallLeaveYear }}" selected>{{ $overallLeaveYear }}</option>
										@else
											<option value="{{ $overallLeaveYear }}">{{ $overallLeaveYear }}</option>
										@endif
									@endforeach
								</select>
							</div>
							<div class="col-md-6">
								<select class="form-control selectpicker" id="overallLeavePic" name="overallLeavePic" onchange="overallLeaveChange();" required>
									<option value="" data-picName="All Person In Charge" selected>All Person In Charge</option>
									@php
										$personInChargeArray = [];
									@endphp
									@foreach ($personInCharges as $personInCharge)
										@if(!in_array($personInCharge->getEmployee->id, $personInChargeArray))
											<option value="{{ $personInCharge->getEmployee->id }}" data-picName="{{ $personInCharge->getEmployee->getFullName() }}">{{ $personInCharge->getEmployee->getFullName() }}</option>
											@php
												$personInChargeArray[] = $personInCharge->getEmployee->id;
											@endphp
										@endif
									@endforeach
								</select>
							</div>
						</div>
					</div>

					<div style="width: 25%; padding-bottom: 25px;">
					</div>

					<div id="overallLeave" style="display:flex;" class="pt-4 justify-content-center"></div>
				</div>
			</div>
		</div>
	@endif
	@if (count($leaveApprovedAndRejectedYears) > 0)
		<div class="row">
			<div class="col-xl-12 mb-30">
				<div class="card-box height-100-p pd-20">
					<h2 class="h4 mb-20">Leave Approved & Rejected</h2>

					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<select class="form-control selectpicker" id="leaveApprovedAndRejectedYear" name="leaveApprovedAndRejectedYear" onchange="leaveApprovedAndRejectedChange();" required>
									@foreach ($leaveApprovedAndRejectedYears as $leaveApprovedAndRejectedYear)
										@if ($loop->iteration == 1)
											<option value="{{ $leaveApprovedAndRejectedYear }}" selected>{{ $leaveApprovedAndRejectedYear }}</option>
										@else
											<option value="{{ $leaveApprovedAndRejectedYear }}">{{ $leaveApprovedAndRejectedYear }}</option>
										@endif
									@endforeach
								</select>
							</div>
							<div class="col-md-6">
								<select class="form-control selectpicker" id="leaveApprovedAndRejectedPic" name="leaveApprovedAndRejectedPic" onchange="leaveApprovedAndRejectedChange();" required>
									<option value="" data-picName="All Person In Charge" selected>All Person In Charge</option>
									@php
										$personInChargeArray = [];
									@endphp
									@foreach ($personInCharges as $personInCharge)
										@if(!in_array($personInCharge->getEmployee->id, $personInChargeArray))
											<option value="{{ $personInCharge->getEmployee->id }}" data-picName="{{ $personInCharge->getEmployee->getFullName() }}">{{ $personInCharge->getEmployee->getFullName() }}</option>
											@php
												$personInChargeArray[] = $personInCharge->getEmployee->id;
											@endphp
										@endif
									@endforeach
								</select>
							</div>
						</div>
					</div>

					<div id="leaveApprovedAndRejected"></div>
				</div>
			</div>
		</div>
	@endif
@endsection

@section('script')
	<script>
		$(document).ready(function (){
			@if (count($overallLeaveYears) > 0)
				overallLeaveChange();
			@endif
			@if (count($leaveApprovedAndRejectedYears) > 0)
				leaveApprovedAndRejectedChange();
			@endif
    	});

		function overallLeaveChange() {
			var year = document.getElementById('overallLeaveYear').value;
			var pic = document.getElementById('overallLeavePic');
			var picID = pic.value;
			var picName = pic.options[pic.selectedIndex].getAttribute('data-picName');
			if(picID == ""){
				picID = null;
			}
			const DATA_URL = "{{ route('overallLeaveAnalytics2', [':year', ':picID']) }}";
			var url = DATA_URL.replace(":year", year);
			url = url.replace(":picID", picID);

			console.log(url);
			console.log(picName);
			
			$.get(url, function(response) {
				overallLeaveChart.updateOptions({
					title:{
						text: 'Overall Leave in ' + year + " (" + picName +")"
					},
					labels: response[0],
					series: response[1]
				})
			});
		}

		function leaveApprovedAndRejectedChange() {
			var year = document.getElementById('leaveApprovedAndRejectedYear').value;
			var pic = document.getElementById('leaveApprovedAndRejectedPic');
			var picID = pic.value;
			var picName = pic.options[pic.selectedIndex].getAttribute('data-picName');
			if(picID == ""){
				picID = null;
			}
			const DATA_URL = "{{ route('leaveApprovedAndRejectedAnalytics2', [':year', ':picID']) }}";
			var url = DATA_URL.replace(":year", year);
			url = url.replace(":picID", picID);
			leaveApprovedAndRejectedChart.updateOptions({
				title:{
					text: 'Leave Approved & Rejected in ' + year + ' (' + picName + ')'
				}
			})
			
			$.get(url, function(response) {
				leaveApprovedAndRejectedChart.updateSeries([{
					name: 'Approved',
					data: response[0]
				},{
					name: 'Rejected',
					data: response[1]
				}])
			});
		}

		var overallLeaveOptions = {
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

		var leaveApprovedAndRejectedOptions = {
			series: [],
			noData:{
				text: 'Loading....'
			},
			title: {
				text: 'Leave Approved & Rejected By Month in 2021',
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
					text: 'Number of leaves'
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

		var overallLeaveChart = new ApexCharts(document.querySelector("#overallLeave"), overallLeaveOptions);
        overallLeaveChart.render();

		var leaveApprovedAndRejectedChart = new ApexCharts(document.querySelector("#leaveApprovedAndRejected"), leaveApprovedAndRejectedOptions);
		leaveApprovedAndRejectedChart.render();
	</script>
@endsection