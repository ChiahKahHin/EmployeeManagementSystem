@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | View Profile
@endsection

@section('pageTitle')
	View Profile
@endsection

@section('content')
	<div class="pd-20 card-box mb-30">
		<div class="clearfix mb-20">
			<div class="pull-left">
				<h4 class="text-blue h4">Profile's Details</h4>
			</div>
		</div>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th scope="col" width="30%">Profile's Details</th>
					<th scope="col" width="70%">Profile's Information</th>
				</tr>
			</thead>
			<tbody>
				@if (!Auth::user()->isAdmin())
					<tr>
						<td class="font-weight-bold">Employee ID</td>
						<td>{{ $employees->employeeID }}</td>
					</tr>
				@endif
				<tr>
					<td class="font-weight-bold">Name</td>
					<td>{{ $employees->getFullName() }}</td>
				</tr>
				@php
					$year = explode("-", $employees->dateOfBirth);
					$age = date('Y') - $year[0];
				@endphp
				<tr>
					<td class="font-weight-bold">Age</td>
					<td>{{ $age }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Date of Birth</td>
					<td>{{ date("d F Y", strtotime($employees->dateOfBirth)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Gender</td>
					<td>{{ ucwords($employees->gender) }}</td>
				</tr>
				@if (!Auth::user()->isAdmin())
					<tr>
						<td class="font-weight-bold">Address</td>
						<td>{!! nl2br($employees->address) !!}</td>
					</tr>
					<tr>
						<td class="font-weight-bold">NRIC</td>
						<td>{{ $employees->ic }}</td>
					</tr>
					<tr>
						<td class="font-weight-bold">Nationality</td>
						<td>{{ $employees->nationality }}</td>
					</tr>
					<tr>
						<td class="font-weight-bold">Citizenship</td>
						<td>{{ $employees->citizenship }}</td>
					</tr>
					<tr>
						<td class="font-weight-bold">Religion</td>
						<td>{{ $employees->religion }}</td>
					</tr>
					<tr>
						<td class="font-weight-bold">Race</td>
						<td>{{ $employees->race }}</td>
					</tr>
					<tr>
						<td class="font-weight-bold">Emergency Contact Name</td>
						<td>{{ $employees->emergencyContactName }}</td>
					</tr>
					<tr>
						<td class="font-weight-bold">Emergency Contact Number</td>
						<td>{{ $employees->emergencyContactNumber }}</td>
					</tr>
					<tr>
						<td class="font-weight-bold">Emergency Contact Address</td>
						<td>{!! nl2br($employees->emergencyContactAddress) !!}</td>
					</tr>
				@endif
				<tr>
					<td class="font-weight-bold">Username</td>
					<td>{{ $employees->username }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Email</td>
					<td>{{ $employees->email }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Contact Number</td>
					<td>{{ $employees->contactNumber }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Department</td>
					<td>{{ $employees->getDepartment->departmentName }}</td>
				</tr>
				@if (!Auth::user()->isAdmin())
					<tr>
						<td class="font-weight-bold">Reporting Manager</td>
						<td>{{ $employees->getFullName($employees->reportingManager) }}</td>
					</tr>
					@if ($employees->role == 1 || $employees->role == 2)
						@php
							$manager = "Yes";
						@endphp
					@else
						@php
							$manager = "No";
						@endphp
					@endif
					
					<tr>
						<td class="font-weight-bold">Department Manager</td>
						<td>{{ $manager }}</td>
					</tr>
				@endif

				<tr>
					<td class="font-weight-bold">Account's Updated (Date & Time)</td>
					<td>{{ date("d F Y, g:ia", strtotime($employees->updated_at)) }}</td>
				</tr>
			</tbody>
		</table>
		<a href="{{ route('updateProfile') }}" class="btn btn-primary btn-block">Update Profile</a>
	</div>
	@if (session('message'))
		<script>
			swal({
				title: '{{ session("message") }}',
				type: 'success',
				confirmButtonClass: 'btn btn-success',
				//timer:5000
			});
		</script>
	@endif
@endsection
