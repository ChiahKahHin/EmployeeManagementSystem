@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Manage Training Program
@endsection

@section('pageTitle')
	Manage Training Program
@endsection

@section('content')
	<div class="card-box mb-30">
		<div class="pd-20">
			<h4 class="text-blue h4 pb-2">All Training Program
				@if (Auth::user()->isAccess('admin', 'hrmanager'))
					<a href="{{ route('addTrainingProgram') }}" style="float: right" class="btn btn-outline-primary">
						<i class="icon-copy dw dw-add"></i> Add Training Program
					</a>
				@endif
			</h4>
			<h6 class="text-blue h6">Training Program Status</h6>
			@php
				$statuses = array("Ongoing", "Completed");
			@endphp
			<select class="w-25 selectpicker" id="status" onchange="changeStatus();">
				<option value="">All Training Program Status</option>
				@foreach ($statuses as $status)
					<option value="{{ $status }}">{{ $status }}</option>
				@endforeach
			</select>
		</div>
		<div class="pb-20">
			<table class="data-table table stripe hover nowrap">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Venue</th>
						<th>Date</th>
						<th>Time</th>
						@if (Auth::user()->isAdmin())
							<th class="text-center">Number of Attendees</th>
						@else
							<th>Registration Status</th>	
						@endif
						<th>Status</th>
						<th class="datatable-nosort">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($trainingPrograms as $trainingProgram)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td class="table-plus">{{ ucfirst($trainingProgram->name) }}</td>
						<td>{{ ucfirst($trainingProgram->venue) }}</td>
						@php
							$dateTime = explode(' ',$trainingProgram->dateAndTime);
						@endphp
						<td>{{ date("d F Y", strtotime($dateTime[0])) }} </td>
						<td>{{ date("g:ia", strtotime($dateTime[1])) }} </td>

						@if (Auth::user()->isAdmin())
							<td class="text-center">{{ $trainingProgram->getAttendees->count() }}</td>
						@else
							<td>{{ $trainingProgram->getRegistrationStatus() }}</td>
						@endif
						
						<td>{{ $trainingProgram->getStatus() }}</td>
						<td>
							<div class="dropdown">
								<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									<i class="dw dw-more"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
									@if (Auth::user()->isAdmin())
										<a class="dropdown-item" href="{{ route('viewTrainingProgram', ['id' => $trainingProgram->id]) }}"><i class="dw dw-eye"></i> View</a>
									@else
										<a class="dropdown-item" href="{{ route('viewTrainingProgram2', ['id' => $trainingProgram->id]) }}"><i class="dw dw-eye"></i> View</a>
									@endif
									@if (Auth::user()->isAdmin() || Auth::user()->isHrManager())
										@if ($trainingProgram->status == 0)
											<a class="dropdown-item" href="{{ route('editTrainingProgram', ['id' => $trainingProgram->id]) }}"><i class="dw dw-edit2"></i> Edit</a>
										@endif
										<a class="dropdown-item deleteTrainingProgram" id="{{ $trainingProgram->id }}" value="{{ $trainingProgram->name }}"><i class="dw dw-delete-3"></i> Delete</a>
									@endif
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
		function changeStatus(){
			table = $(".table").dataTable();
			let value = document.getElementById('status').value;
			table.fnFilter(value, 6, false, true, true, true);
		}
		
        $(document).on('click', '.deleteTrainingProgram', function() {
            var trainingProgramID = $(this).attr('id');
            var trainingProgramName = $(this).attr('value');
			const DELETE_URL = "{{ route('deleteTrainingProgram', ':id') }}";
			var url = DELETE_URL.replace(":id", trainingProgramID);

            swal({
                title: 'Delete this training program?',
                text: 'Training Program Name: ' + trainingProgramName,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value){
                    swal({
                        title: "Deleted!",
                        text: "Training program removed from system",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = url;
                    });
                }
                else{
                    swal("Cancelled", "Training program is not removed from system", "error");
                }
            });
        });
    </script>
@endsection