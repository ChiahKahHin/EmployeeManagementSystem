@extends('layouts.template')

@section('title')
    {{ Auth::user()->getRoleName() }} | View Admin Details
@endsection

@section('pageTitle')
    View Admin Details
@endsection

@section('content')
    <div class="pd-20 card-box mb-30">
        <div class="clearfix mb-20">
            <div class="pull-left">
                <h4 class="text-blue h4">Admin's Details</h4>
            </div>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col" width="30%">Admin's Details</th>
                    <th scope="col" width="70%">Admin's Information</th>
                </tr>
            </thead>
            <tbody>
				<tr>
					<td class="font-weight-bold">Name</td>
					<td>{{ ucwords($admin->firstname) }} {{ ucwords($admin->lastname) }}</td>
				</tr>
				@php
					$year = explode("-", $admin->dateOfBirth);
					$age = date('Y') - $year[0];
				@endphp
				<tr>
					<td class="font-weight-bold">Age</td>
					<td>{{ $age }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Date of Birth</td>
					<td>{{ date("d F Y", strtotime($admin->dateOfBirth)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Gender</td>
					<td>{{ ucwords($admin->gender) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Username</td>
					<td>{{ $admin->username }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Email</td>
					<td>{{ $admin->email }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Contact Number</td>
					<td>{{ $admin->contactNumber }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Department</td>
					<td>{{ $admin->getDepartment->departmentName }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Admin's Account Created Date & Time</td>
					<td>{{ date("d F Y, G:ia", strtotime($admin->created_at)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Admin's Account Updated Date & Time</td>
					<td>{{ date("d F Y, G:ia", strtotime($admin->updated_at)) }}</td>
				</tr>
				
            </tbody>
        </table>
    </div>
@endsection
