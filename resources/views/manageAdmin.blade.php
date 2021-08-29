@extends('layouts.template')

@section('title')
    {{ Auth::user()->getRoleName() }} | Manage Admin
@endsection

@section('pageTitle')
    Manage Admin
@endsection

@section('content')
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">All Admin</h4>
        </div>
        <div class="pb-20">
            <table class="data-table table stripe hover nowrap">
                <thead>
                    <tr>
						<th>#</th>
                        {{-- <th class="table-plus datatable-nosort">Name</th> --}}
                        <th>Name</th>
                        <th>Age</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Contact Number</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
					@foreach ($admins as $admin)
						@php
							$year = explode("-", $admin->dateOfBirth);
							$age = date('Y') - $year[0];
						@endphp
                    <tr>
						<td>{{ $loop->iteration }}</td>
						<td class="table-plus">{{ $admin->getFullName() }}</td>
                        <td>{{ $age }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->username }}</td>
                        <td>{{ $admin->contactNumber }} </td>
                        <td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									<a class="dropdown-item" href="{{ route('viewAdmin', ['id' => $admin->id]) }}"><i class="dw dw-eye"></i> View</a>
									<a class="dropdown-item" href="{{ route('editAdmin', ['id' => $admin->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
									<a class="dropdown-item deleteAdmin" id="{{ $admin->id }}" value="{{ $admin->username }}"><i class="dw dw-delete-3"></i> Delete</a>
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
        $(document).on('click', '.deleteAdmin', function() {
            var adminID = $(this).attr('id');
            var adminUsername = $(this).attr('value');
            swal({
                title: 'Delete this admin?',
                text: 'Username: ' + adminUsername,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value){
                    swal({
                        title: "Deleted!",
                        text: "Admin removed from system",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/deleteAdmin/" + adminID;
                    });
                }
                else{
                    swal("Cancelled", "Admin is not removed from system", "error");
                }
            });
        });
    </script>
    
@endsection