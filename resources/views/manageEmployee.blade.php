@extends('layouts.template')

@section('title')
    {{ Auth::user()->getRoleName() }} | Manage Employee
@endsection

@section('pageTitle')
    Manage Employee
@endsection

@section('content')
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">All Employees</h4>
        </div>
        <div class="pb-20">
            <table class="data-table table stripe hover nowrap">
                <thead>
                    <tr>
						<th>#</th>
                        {{-- <th class="table-plus datatable-nosort">Name</th> --}}
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Department</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
					@foreach ($employees as $employee)
						@php
							$year = explode("-", $employee->dateOfBirth);
							$age = date('Y') - $year[0];
						@endphp
                    <tr>
						<td>{{ $loop->iteration }}</td>
                        <td>{{ $employee->employeeID }}</td>
						<td class="table-plus">{{ $employee->getFullName($employee->id) }}</td>
                        <td>{{ $age }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->contactNumber }} </td>
                        <td>{{ ucwords($employee->getDepartment->departmentName) }}</td>
                        <td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									<a class="dropdown-item" href="{{ route('viewEmployee', ['id' => $employee->id]) }}"><i class="dw dw-eye"></i> View</a>
									<a class="dropdown-item" href="{{ route('editEmployee', ['id' => $employee->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
									<a class="dropdown-item deleteEmployee" id="{{ $employee->id }}" value="{{ $employee->getFullName($employee->id) }}"><i class="dw dw-delete-3"></i> Delete</a>
								</div>
							</div>
						</td>
					</tr>
					@endforeach
                    
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', '.deleteEmployee', function() {
            var employeeID = $(this).attr('id');
            var employeeUsername = $(this).attr('value');
            swal({
                title: 'Delete this employee?',
                text: 'Username: ' + employeeUsername,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value){
                    swal({
                        title: "Deleted!",
                        text: "Employee removed from system",
                        type: "success",
                        showCancelButton: false,
                        timer: 3000
                    }).then(function(){
                        window.location.href = "/deleteEmployee/" + employeeID;
                    });
                }
                else{
                    swal("Cancelled", "Employee is not removed from system", "error");
                }
            });
        });
    </script>
    
@endsection