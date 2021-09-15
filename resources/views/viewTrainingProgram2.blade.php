@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Training Program Details
@endsection

@section('pageTitle')
	Training Program Details
@endsection

@section('content')
	<div class="pd-20 card-box mb-30">
		<div class="clearfix mb-20">
			<div class="pull-left">
				<h4 class="text-blue h4">Training Program Details</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<img id="img-modal" style="width: 500px; height:550px;" src="data:image/png;base64,{{ chunk_split(base64_encode($trainingProgram->poster)) }}" alt="{{ $trainingProgram->name }}">
				<div id="image-modal" class="img-modal">
					<span class="close">&times;</span>
					<img class="img-modal-content" id="img-block-preview">
					<div id="caption"></div>
				</div>
			</div>
			<div class="col-md-8">
				<table class="table table-bordered table-striped">
					<thead>
						<tr class=" text-center">
							<th scope="col" width="30%">Training Program Details</th>
							<th scope="col" width="70%">Training Program Information</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="font-weight-bold">Name</td>
							<td>{{ ucfirst($trainingProgram->name) }}</td>
						</tr>
						<tr>
							<td class="font-weight-bold">Description</td>
							<td class="text-justify">{!! nl2br($trainingProgram->description) !!}</td>
						</tr>
						<tr>
							<td class="font-weight-bold">Venue</td>
							<td>{{ ucfirst($trainingProgram->venue) }}</td>
						</tr>
		
						@php
							$dateTime = explode(' ',$trainingProgram->dateAndTime);
						@endphp				
						<tr>
							<td class="font-weight-bold">Date</td>
							<td>{{ date("d F Y", strtotime($dateTime[0])) }} </td>
						</tr>
						<tr>
							<td class="font-weight-bold">Time</td>
							<td>{{ date("g:ia", strtotime($dateTime[1])) }} </td>
						</tr>
		
						@if ($trainingProgram->department != null)
							<tr>
								<td class="font-weight-bold">Targeted Attendee <i>(Department)</i></td>
								<td>{{ ucfirst($trainingProgram->getDepartment->departmentName) }} Department</td>
							</tr>
						@else
							<tr>
								<td class="font-weight-bold">Attendee</td>
								<td>All Employees</td>
							</tr>	
						@endif
						
						<tr>
							<td class="font-weight-bold">Registration Status</td>
							<td>{{ $trainingProgram->getRegistrationStatus() }}</td>
						</tr>
						<tr>
							<td class="font-weight-bold">Training Program Status</td>
							<td>{{ $trainingProgram->getStatus() }}</td>
						</tr>
					</tbody>
				</table>
				@if (Auth::user()->isAccess('hrmanager', 'manager', 'employee'))
					@if ($trainingProgram->status == 0)
						<div class="row">
							<div class="col-md-12">
								@if ($trainingProgram->getRegistrationStatus() == "Not yet register")
									<button type="button" id="register" class="btn btn-primary btn-block">Register Training Program</button>
								@else
									<button type="button" id="cancelRegister" class="btn btn-primary btn-block">Cancel Registeration</button>
								@endif
							</div>
						</div>
					@endif
				@endif
			</div>
		</div>
		
	</div>
@endsection

@section("script")
	<script src="{{ asset('vendors/scripts/image-modal.js') }}"></script>
	<script>
		$('#register').on('click', function(){
			swal({
				title: "Register training program?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonClass: "btn btn-success",
				confirmButtonText: "Register"
			}).then((result) => {
				if(result.value){
					swal({
                        title: "Register Successfully!",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/registerTrainingProgram/" + {{ $trainingProgram->id }};
                    });
				}
				else{
					swal("Cancelled", "Training program is not registered", "error");
				}
			});
		});

		$('#cancelRegister').on('click', function(){
			swal({
				title: "Cancel registration?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonClass: "btn btn-success",
				confirmButtonText: "Cancel"
			}).then((result) => {
				if(result.value){
					swal({
                        title: "Cancel Successfully!",
                        type: "success",
                        showCancelButton: false,
                        //timer:3000
                    }).then(function(){
                        window.location.href = "/cancelTrainingProgram/" + {{ $trainingProgram->id }};
                    });
				}
				else{
					swal("Cancelled", "Training program is not cancelled", "error");
				}
			});
		});
	</script>
@endsection