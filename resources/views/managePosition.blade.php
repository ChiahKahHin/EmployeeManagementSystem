@extends('layouts.template')

@section('title')
    {{ Auth::user()->getRoleName() }} | Manage Positions
@endsection

@section('pageTitle')
    Manage Positions
@endsection

@section('content')
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">All Positions
                <a href="{{ route('addPosition') }}" style="float: right" class="btn btn-outline-primary">
                    <i class="icon-copy dw dw-add"></i> Add Position
                </a>
            </h4>
        </div>
        <div class="pb-20">
            <table class="data-table table stripe hover nowrap">
                <thead>
                    <tr>
						<th width="5%" style="text-align: center">#</th>
                        <th>Position Name</th>
                        <th class="datatable-nosort" width="15%" style="text-align: center">Action</th>
                    </tr>
                </thead>
                <tbody>
					@foreach ($positions as $position)
                    <tr>
						<td style="text-align: center">{{ $loop->iteration }}</td>
                        <td>{{ ucwords($position->positionName) }}</td>
                        <td style="text-align: center">
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									<a class="dropdown-item" href="{{ route('editPosition', ['id' => $position->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
									<a class="dropdown-item deletePosition" id="{{ $position->id }}" value="{{ ucwords($position->positionName) }}"><i class="dw dw-delete-3"></i> Delete</a>
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
        $(document).on('click', '.deletePosition', function() {
            var positionId = $(this).attr('id');
            var positionName = $(this).attr('value');
            swal({
                title: 'Delete this position?',
                text: 'Position: ' + positionName,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value){
                    swal({
                        title: "Deleted!",
                        text: positionName + " position removed from system",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/deletePosition/" + positionId;
                    });
                }
                else{
                    swal("Cancelled", "Position is not removed from system", "error");
                }
            });
        });
    </script>
    
@endsection