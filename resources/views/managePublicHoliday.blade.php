@extends('layouts.template')

@section('title')
    {{ Auth::user()->getRoleName() }} | Manage Public Holidays
@endsection

@section('pageTitle')
    Manage Public Holidays
@endsection

@section('content')
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">All Public Holidays</h4>
        </div>
        <div class="pb-20">
            <table class="data-table table stripe hover nowrap">
                <thead>
                    <tr>
						<th>#</th>
                        <th>Public Holiday Name</th>
                        <th>Public Holiday Date</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
					@foreach ($publicHolidays as $publicHoliday)
                    <tr>
						<td>{{ $loop->iteration }}</td>
						<td class="table-plus">{{ ucwords($publicHoliday->name) }}</td>
                        <td>{{ ucwords($publicHoliday->date) }}</td>
                        <td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									{{-- <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a> --}}
									<a class="dropdown-item" href="{{ route('editPublicHoliday', ['id' => $publicHoliday->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
									<a class="dropdown-item deletePublicHoliday" id="{{ $publicHoliday->id }}" value="{{ ucwords($publicHoliday->name) }}"><i class="dw dw-delete-3"></i> Delete</a>
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
        $(document).on('click', '.deletePublicHoliday', function() {
            var publicHolidayId = $(this).attr('id');
            var publicHolidayName = $(this).attr('value');
            swal({
                title: 'Delete this public holiday?',
                text: 'Public Holiday: ' + publicHolidayName,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value){
                    swal({
                        title: "Deleted!",
                        text: publicHolidayName + " public holiday removed from system",
                        type: "success",
                        showCancelButton: false,
                        timer: 3000
                    }).then(function(){
                        window.location.href = "/deletePublicHoliday/" + publicHolidayId;
                    });
                }
                else{
                    swal("Cancelled", "Public holiday is not removed from system", "error");
                }
            });
        });
    </script>
    
@endsection