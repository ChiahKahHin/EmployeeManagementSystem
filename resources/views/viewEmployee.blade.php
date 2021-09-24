@extends('layouts.template')

@section('title')
    {{ Auth::user()->getRoleName() }} | View Employee Details
@endsection

@section('pageTitle')
    View Employee Details
@endsection

@section('content')
    <div class="pd-20 card-box mb-30">
        <div class="clearfix mb-5">
            <div class="pull-left">
                <h4 class="text-blue h4">Employee's Details</h4>
            </div>
        </div>
		<div class="profile-tab height-100-p">
			<div class="tab height-100-p">
				@if($employees->getEmployeeInfo->maritalStatus == "Married")
					<ul class="nav nav-tabs customtab" role="tablist">
						<li class="nav-item" style="width: 25%">
							<a class="nav-link active" data-toggle="tab" href="#personalInformation" role="tab" style="text-align: center;">Personal Information</a>
						</li>
						<li class="nav-item" style="width: 25%">
							<a class="nav-link" data-toggle="tab" href="#spouseInformation" role="tab" style="text-align: center;">Spouse Information</a>
						</li>
						<li class="nav-item" style="width: 25%">
							<a class="nav-link" data-toggle="tab" href="#emergencyContact" role="tab" style="text-align: center;">Emergency Contact</a>
						</li>
						<li class="nav-item" style="width: 25%">
							<a class="nav-link" data-toggle="tab" href="#accountInformation" role="tab" style="text-align: center;">Account Information</a>
						</li>
					</ul>
				@else
				<ul class="nav nav-tabs customtab" role="tablist">
					<li class="nav-item" style="width: 33.33%">
						<a class="nav-link active" data-toggle="tab" href="#personalInformation" role="tab" style="text-align: center;">Personal Information</a>
					</li>
					<li class="nav-item" style="width: 33.33%">
						<a class="nav-link" data-toggle="tab" href="#emergencyContact" role="tab" style="text-align: center;">Emergency Contact</a>
					</li>
					<li class="nav-item" style="width: 33.33%">
						<a class="nav-link" data-toggle="tab" href="#accountInformation" role="tab" style="text-align: center;">Account Information</a>
					</li>
				</ul>
				@endif
				<div class="tab-content">
					<!-- Personal Information Tab start -->
					<div class="tab-pane fade show active" id="personalInformation" role="tabpanel">
						<div class="pd-5">
							<div class="profile-timeline" style="padding: 5px;">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th scope="col" width="30%">Employee's Details</th>
											<th scope="col" width="70%">Employee's Information</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="font-weight-bold">Name</td>
											<td>{{ $employees->getFullName() }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Contact Number</td>
											<td>{{ $employees->getEmployeeInfo->contactNumber }}</td>
										</tr>
										@php
											$year = explode("-", $employees->getEmployeeInfo->dateOfBirth);
											$age = date('Y') - $year[0];
										@endphp
										<tr>
											<td class="font-weight-bold">Age</td>
											<td>{{ $age }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Date of Birth</td>
											<td>{{ date("d F Y", strtotime($employees->getEmployeeInfo->dateOfBirth)) }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Gender</td>
											<td>{{ ucwords($employees->getEmployeeInfo->gender) }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Address</td>
											<td>{!! nl2br($employees->getEmployeeInfo->address) !!}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">NRIC/Passport No</td>
											<td>{{ $employees->getEmployeeInfo->ic }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Nationality</td>
											<td>{{ $employees->getEmployeeInfo->nationality }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Citizenship</td>
											<td>{{ $employees->getEmployeeInfo->citizenship }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Religion</td>
											<td>{{ $employees->getEmployeeInfo->religion }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Race</td>
											<td>{{ $employees->getEmployeeInfo->race }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Marital Status</td>
											<td>{{ $employees->getEmployeeInfo->maritalStatus }}</td>
										</tr>										
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- Personal Information Tab End -->

					<!-- Spouse Information Tab start -->
					<div class="tab-pane fade" id="spouseInformation" role="tabpanel">
						<div class="pd-5">
							<div class="profile-timeline" style="padding: 5px;">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th scope="col" width="30%">Employee's Details</th>
											<th scope="col" width="70%">Employee's Information</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="font-weight-bold">Spouse Name</td>
											<td>{{ $employees->getEmployeeInfo->spouseName }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Spouse Date of Birth</td>
											<td>{{ date("d F Y", strtotime($employees->getEmployeeInfo->spouseDateOfBirth)) }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Spouse NRIC/Passport No</td>
											<td>{{ $employees->getEmployeeInfo->spouseIC }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Date of Marriage</td>
											<td>{{ date("d F Y", strtotime($employees->getEmployeeInfo->dateOfMarriage)) }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Spouse Occupation</td>
											<td>{{ $employees->getEmployeeInfo->spouseOccupation }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Spouse Contact Number</td>
											<td>{{ $employees->getEmployeeInfo->spouseContactNumber }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Spouse Resident Status</td>
											<td>{{ $employees->getEmployeeInfo->spouseResidentStatus }}</td>
										</tr>									
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- Spouse Information Tab End -->
					
					<!-- Emergency Contact Tab start -->
					<div class="tab-pane fade" id="emergencyContact" role="tabpanel">
						<div class="pd-5">
							<div class="profile-timeline" style="padding: 5px;">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th scope="col" width="30%">Employee's Details</th>
											<th scope="col" width="70%">Employee's Information</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="font-weight-bold">Emergency Contact Name</td>
											<td>{{ $employees->getEmployeeInfo->emergencyContactName }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Emergency Contact Number</td>
											<td>{{ $employees->getEmployeeInfo->emergencyContactNumber }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Emergency Contact Address</td>
											<td>{!! nl2br($employees->getEmployeeInfo->emergencyContactAddress) !!}</td>
										</tr>										
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- Emergency Contact Tab End -->

					<!-- Emergency Contact Tab start -->
					<div class="tab-pane fade" id="accountInformation" role="tabpanel">
						<div class="pd-5">
							<div class="profile-timeline" style="padding: 5px;">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th scope="col" width="30%">Employee's Details</th>
											<th scope="col" width="70%">Employee's Information</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="font-weight-bold">Username</td>
											<td>{{ $employees->username }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Email</td>
											<td>{{ $employees->email }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Employee ID</td>
											<td>{{ $employees->employeeID }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Department</td>
											<td>{{ $employees->getDepartment->departmentName }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Position</td>
											<td>{{ $employees->getPosition->positionName }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Reporting Manager</td>
											<td>{{ $employees->getFullName($employees->reportingManager) }}</td>
										</tr>										
										<tr>
											<td class="font-weight-bold">System Role</td>
											<td>{{ $employees->getRoleName() }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Employee's Account Created Date & Time</td>
											<td>{{ date("d F Y, g:ia", strtotime($employees->created_at)) }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Employee's Account Updated Date & Time</td>
											<td>{{ date("d F Y, g:ia", strtotime($employees->updated_at)) }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- Emergency Contact Tab End -->
				</div>
			</div>
		</div>
    </div>
@endsection
