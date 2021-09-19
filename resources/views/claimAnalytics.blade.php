@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Benefit Claim Analytics
@endsection

@section('pageTitle')
	Benefit Claim Analytics
@endsection

@section('content')
	@if (count($overallClaimYears) == 0 && count($claimApprovedAndRejectedYears) == 0 && count($claimTypeApprovedYears) == 0)
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
					<h2 class="h4 mb-20">Overall Benefit Claim</h2>

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
								<div class="col-md-6">
									<select class="form-control selectpicker" id="overallClaimDepartment" name="overallClaimDepartment" onchange="overallClaimChange();" required>
										<option value="" data-departmentName="All Departments" selected>All Departments</option>
										@php
											$departmentArray = [];
										@endphp
										@foreach ($departments as $department)
											@if(!in_array($department->getEmployee->getDepartment->id, $departmentArray))
												<option value="{{ $department->getEmployee->getDepartment->id }}" data-departmentName="{{ $department->getEmployee->getDepartment->departmentName }}">{{ $department->getEmployee->getDepartment->departmentName }}</option>
												@php
													$departmentArray[] = $department->getEmployee->getDepartment->id;
												@endphp
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
							<div class="col-md-6">
								<select class="form-control selectpicker" id="claimApprovedAndRejectedDepartment" name="claimApprovedAndRejectedDepartment" onchange="claimApprovedAndRejectedChange();" required>
									<option value="" data-departmentName="All Departments" selected>All Departments</option>
									@php
										$departmentArray = [];
									@endphp
									@foreach ($departments as $department)
										@if ($department->claimStatus == 1 || $department->claimStatus == 2)
											@if(!in_array($department->getEmployee->getDepartment->id, $departmentArray))
												<option value="{{ $department->getEmployee->getDepartment->id }}" data-departmentName="{{ $department->getEmployee->getDepartment->departmentName }}">{{ $department->getEmployee->getDepartment->departmentName }}</option>
												@php
													$departmentArray[] = $department->getEmployee->getDepartment->id;
												@endphp
											@endif
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

	@if (count($claimTypeApprovedYears) > 0)
		<div class="row">
			<div class="col-xl-12 mb-30">
				<div class="card-box height-100-p pd-20">
					<h2 class="h4 mb-20">Benefit Claim Approved By Claim Type</h2>

					<div class="form-group">
						<div class="row">
							<div class="col-md-4">
								<select class="form-control selectpicker" id="claimTypeApprovedYear" name="claimTypeApprovedYear" onchange="claimTypeApprovedChange();" required>
									@foreach ($claimTypeApprovedYears as $claimTypeApprovedYear)
										@if ($loop->iteration == 1)
											<option value="{{ $claimTypeApprovedYear }}" selected>{{ $claimTypeApprovedYear }}</option>
										@else
											<option value="{{ $claimTypeApprovedYear }}">{{ $claimTypeApprovedYear }}</option>
										@endif
									@endforeach
								</select>
							</div>
							<div class="col-md-4">
								<select class="form-control selectpicker" id="claimTypeApprovedDepartment" name="claimTypeApprovedDepartment" onchange="claimTypeApprovedChange();" required>
									<option value="" data-departmentName="All Departments" selected>All Departments</option>
									@php
										$departmentArray = [];
									@endphp
									@foreach ($departments as $department)
										@if ($department->claimStatus == 2)
											@if(!in_array($department->getEmployee->getDepartment->id, $departmentArray))
												<option value="{{ $department->getEmployee->getDepartment->id }}" data-departmentName="{{ $department->getEmployee->getDepartment->departmentName }}">{{ $department->getEmployee->getDepartment->departmentName }}</option>
												@php
													$departmentArray[] = $department->getEmployee->getDepartment->id;
												@endphp
											@endif
										@endif
									@endforeach
								</select>
							</div>
							<div class="col-md-4">
								<select class="form-control selectpicker" id="claimType" name="claimType" onchange="claimTypeApprovedChange();" required>
									<option value="" data-claimType="All Claim Types" selected>All Claim Types</option>
									@php
										$claimTypesArray = [];
									@endphp
									@foreach ($claimTypes as $claimType)
										@if ($claimType->claimStatus == 2)
											@if(!in_array($claimType->getClaimType->id, $claimTypesArray))
												<option value="{{ $claimType->getClaimType->id }}" data-claimType="{{ $claimType->getClaimType->claimType }}">{{ $claimType->getClaimType->claimType }}</option>
												@php
													$claimTypesArray[] = $claimType->getClaimType->id;
												@endphp
											@endif
										@endif
									@endforeach
								</select>
							</div>
						</div>
					</div>

					<div id="claimApprovedLeaveType"></div>
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
			@if (count($claimTypeApprovedYears) > 0)
				claimTypeApprovedChange();
			@endif
    	});

		function overallClaimChange() {
			var year = document.getElementById('overallClaimYear').value;
			var department = document.getElementById('overallClaimDepartment');
			var departmentID = department.value;
			var departmentName = department.options[department.selectedIndex].getAttribute('data-departmentName');
			if(departmentID == ""){
				departmentID = null;
			}
			const DATA_URL = "{{ route('overallClaimAnalytics', [':year', ':departmentID']) }}";
			var url = DATA_URL.replace(":year", year);
			url = url.replace(":departmentID", departmentID);

			console.log(url);
			console.log(departmentName);
			
			$.get(url, function(response) {
				overallClaimChart.updateOptions({
					title:{
						text: 'Overall Claim in ' + year + " (" + departmentName +")"
					},
					labels: response[0],
					series: response[1]
				})
			});
		}

		function claimApprovedAndRejectedChange() {
			var year = document.getElementById('claimApprovedAndRejectedYear').value;
			var department = document.getElementById('claimApprovedAndRejectedDepartment');
			var departmentID = department.value;
			var departmentName = department.options[department.selectedIndex].getAttribute('data-departmentName');
			if(departmentID == ""){
				departmentID = null;
			}
			const DATA_URL = "{{ route('claimApprovedAndRejectedAnalytics', [':year', ':departmentID']) }}";
			var url = DATA_URL.replace(":year", year);
			url = url.replace(":departmentID", departmentID);
			claimApprovedAndRejectedChart.updateOptions({
				title:{
					text: 'Benefit Claim Approved & Rejected in ' + year + ' (' + departmentName + ')'
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

		function claimTypeApprovedChange() {
			var year = document.getElementById('claimTypeApprovedYear').value;
			var department = document.getElementById('claimTypeApprovedDepartment');
			var departmentID = department.value;
			var departmentName = department.options[department.selectedIndex].getAttribute('data-departmentName');
			var claimType = document.getElementById('claimType');
			var claimTypeID = claimType.value;
			var claimTypeName = claimType.options[claimType.selectedIndex].getAttribute('data-claimType');

			if(departmentID == ""){
				departmentID = null;
			}
			if(claimTypeID == ""){
				claimTypeID = null;
			}
			const DATA_URL = "{{ route('claimTypeApprovedAnalytics', [':year', ':departmentID', ':claimTypeID']) }}";
			var url = DATA_URL.replace(":year", year);
			url = url.replace(":departmentID", departmentID);
			url = url.replace(":claimTypeID", claimTypeID);

			claimApprovedLeaveTypeChart.updateOptions({
				title:{
					text: claimTypeName + ' Approved in ' + year + ' (' + departmentName + ')'
				}
			})
			
			$.get(url, function(response) {
				claimApprovedLeaveTypeChart.updateSeries([{
					name: 'Claim Amount (RM)',
					data: response
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
					text: 'Number of claim request'
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

		var claimApprovedLeaveTypeOptions = {
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
					text: 'Claim Amount (RM)'
				}
			}
		}

		var overallClaimChart = new ApexCharts(document.querySelector("#overallClaim"), overallClaimOptions);
        overallClaimChart.render();

		var claimApprovedAndRejectedChart = new ApexCharts(document.querySelector("#claimApprovedAndRejected"), claimApprovedAndRejectedOptions);
		claimApprovedAndRejectedChart.render();

		var claimApprovedLeaveTypeChart = new ApexCharts(document.querySelector("#claimApprovedLeaveType"), claimApprovedLeaveTypeOptions);
		claimApprovedLeaveTypeChart.render();
	</script>
@endsection