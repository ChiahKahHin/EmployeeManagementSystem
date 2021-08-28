@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | Training Program Details
@endsection

@section('pageTitle')
	Training Program Details
@endsection

@section('content')
	{{-- <div class="min-height-200px">
		<div class="page-header">
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="title">
						<h4>Training Program Details</h4>
					</div>
				</div>
			</div>
		</div>
		<div class="product-wrap">
			<div class="product-detail-wrap mb-30">
				<div class="row">
					<div class="col-md-4">
						<img src="{{ asset('vendors/images/product-img1.jpg') }}" alt="">
					</div>
					<div class="col-md-8">
						<div class="product-detail-desc pd-20 card-box height-100-p">
							<h4 class="mb-20 pt-20">Gufram Bounce Black</h4>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
							<div class="price">
								<del>$55.5</del><ins>$49.5</ins>
							</div>
							<div class="mx-w-150">
								<div class="form-group">
									<label class="text-blue">quantity</label>
									<input id="demo3_22" type="text" value="1" name="demo3_22">
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-6">
									<a href="#" class="btn btn-primary btn-block">Add To Cart</a>
								</div>
								<div class="col-md-6 col-6">
									<a href="#" class="btn btn-outline-primary btn-block">Buy Now</a>
								</div>
								@if (Auth::user()->isManager() || Auth::user()->isEmployee())
									@if ($trainingProgram->status == 0)
										<div class="row">
											<div class="col-md-12">
												<button type="button" id="approveClaimRequest" class="btn btn-primary btn-block">Register Training Program</button>
											</div>
										</div>
									@endif
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> --}}
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
				@if (Auth::user()->isManager() || Auth::user()->isEmployee())
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