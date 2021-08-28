@extends('layouts.template')

@section('title')
    {{ Auth::user()->getRoleName() }} | Manage Departments
@endsection

@section('pageTitle')
    Manage Departments
@endsection

@section('content')
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">All Departments</h4>
        </div>
        <div class="pb-20">
            <table class="data-table table stripe hover nowrap">
                <thead>
                    <tr>
						<th>#</th>
                        <th>Department Code</th>
                        <th>Department Name</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
					@foreach ($departments as $department)
                    <tr>
						<td>{{ $loop->iteration }}</td>
						<td class="table-plus">{{ ucwords($department->departmentCode) }}</td>
                        <td>{{ ucwords($department->departmentName) }}</td>
                        <td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									{{-- <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a> --}}
									<a class="dropdown-item" href="{{ route('editDepartment', ['id' => $department->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
									<a class="dropdown-item deleteDepartment" id="{{ $department->id }}" value="{{ ucwords($department->departmentName) }}"><i class="dw dw-delete-3"></i> Delete</a>
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
        $(document).on('click', '.deleteDepartment', function() {
            var departmentId = $(this).attr('id');
            var departmentName = $(this).attr('value');
            swal({
                title: 'Delete this department?',
                text: 'Department: ' + departmentName,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value){
                    swal({
                        title: "Deleted!",
                        text: departmentName + " department removed from system",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/deleteDepartment/" + departmentId;
                    });
                }
                else{
                    swal("Cancelled", "Department is not removed from system", "error");
                }
            });
        });
    </script>
    
@endsection