@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | View Training Program Details
@endsection

@section('pageTitle')
	View Training Program Details
@endsection

@section('content')
	<div class="pd-20 card-box mb-30">
		<div class="clearfix mb-5">
			<div class="pull-left">
				<h4 class="text-blue h4">Training Program Details</h4>
			</div>
		</div>
		<div class="profile-tab height-100-p">
			<div class="tab height-100-p">
				<ul class="nav nav-tabs customtab" role="tablist">
					<li class="nav-item" style="width: 50%">
						<a class="nav-link active" data-toggle="tab" href="#trainingProgramDetails" role="tab" style="text-align: center;">Training Program Details</a>
					</li>
					<li class="nav-item" style="width: 50%">
						<a class="nav-link" data-toggle="tab" href="#attendesList" role="tab" style="text-align: center;">Attendee List</a>
					</li>
				</ul>
				<div class="tab-content">					
					<!-- Emergency Contact Tab start -->
					<div class="tab-pane fade show active" id="trainingProgramDetails" role="tabpanel">
						<div class="pd-5">
							<div class="profile-timeline" style="padding: 5px;">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th scope="col" width="25%">Training Program Details</th>
											<th scope="col" width="75%">Training Program Information</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="font-weight-bold">Name</td>
											<td>{{ ucfirst($trainingProgram->name) }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Description</td>
											<td>{!! nl2br($trainingProgram->description) !!}</td>
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
											<td class="font-weight-bold">Number of Attendees</td>
											<td>{{ $trainingProgram->getAttendees->count() }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Status</td>
											<td>{{ $trainingProgram->getStatus() }}</td>
										</tr>
						
										<tr>
											<td class="font-weight-bold">Poster</td>
											<td>
												<img id="img-modal" src="data:image/png;base64,{{ chunk_split(base64_encode($trainingProgram->poster)) }}" alt="{{ $trainingProgram->name }}">
												<div id="image-modal" class="img-modal">
													<span class="close">&times;</span>
													<img class="img-modal-content" id="img-block-preview">
													<div id="caption"></div>
												</div>
											</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Training Program Created Date & Time</td>
											<td>{{ date("d F Y, g:ia", strtotime($trainingProgram->created_at)) }}</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Training Program Updated Date & Time</td>
											<td>{{ date("d F Y, g:ia", strtotime($trainingProgram->updated_at)) }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- Emergency Contact Tab End -->

					<!-- Emergency Contact Tab start -->
					<div class="tab-pane fade" id="attendesList" role="tabpanel">
						<div class="pd-5">
							<div class="profile-timeline" style="padding: 5px;">
								<table class="table table-bordered table-striped">
									<thead>
										@if (count($trainingAttendees) != 0)
											<tr>
												<th scope="col" width="5%">No</th>
												<th scope="col" width="95%">Attendees Name</th>
											</tr>
										@else
											<tr>
												<th><i><center>No attendees at the moment</center></i></th>
											</tr>
										@endif
									</thead>
									<tbody>						
										@if (count($trainingAttendees) != 0)
											@foreach ($trainingAttendees as $trainingAttendee)
											<tr>
												<td class="font-weight-bold">{{ $loop->iteration }}</td>
												<td>
													{{ $trainingAttendee->getEmployee->getFullName() }}
												</td>
											</tr>
											@endforeach											
										@endif
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section("script")
	<script src="{{ asset('vendors/scripts/image-modal.js') }}"></script>
@endsection
