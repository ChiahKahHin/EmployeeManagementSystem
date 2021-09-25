@extends('layouts.template')

@section('title')
    {{ Auth::user()->getRoleName() }} | View Delegation Details
@endsection

@section('pageTitle')
    View Delegation Details
@endsection

@section('content')
	@if ($delegation->status <= 2)
		<div class="card">
			<div class="row d-flex justify-content-between top-progressbar3">
				<div class="d-flex pb-4">
					<h5>Delegation Status</h5>
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<div class="col-12">
					<ul id="progressbar3" class="text-center">
						<li class="@if($delegation->status >= 0) active @endif step0" @if($delegation->status >= 0) style="cursor: pointer;" data-toggle="modal" data-target="#scheduling-modal" @endif></li>
						<li class="@if($delegation->status >= 1) active @endif step0" @if($delegation->status >= 1) style="cursor: pointer;" data-toggle="modal" data-target="#ongoing-modal" @endif></li>
						<li class="@if($delegation->status >= 2) active @endif step0" @if($delegation->status >= 1) style="cursor: pointer;" data-toggle="modal" data-target="#completed-modal" @endif></li>
					</ul>
				</div>
			</div>

			<div class="row justify-content-between top-progressbar3">
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Scheduling</p>
					</div>
				</div>
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Ongoing</p>
					</div>
				</div>
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Completed<br></p>
					</div>
				</div>
			</div>
		</div>
	@elseif ($delegation->status <= 3)
		<div class="card">
			<div class="row d-flex justify-content-between top-progressbar2">
				<div class="d-flex pb-4">
					<h5>Delegation Status</h5>
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<div class="col-12">
					<ul id="progressbar2" class="text-center">
						<li class="@if($delegation->status >= 0) active @endif step0" @if($delegation->status >= 0) style="cursor: pointer;" data-toggle="modal" data-target="#scheduling-modal" @endif></li>
						<li class="@if($delegation->status >= 3) active @endif step0" @if($delegation->status >= 3) style="cursor: pointer;" data-toggle="modal" data-target="#cancelled-modal" @endif></li>
					</ul>
				</div>
			</div>

			<div class="row justify-content-between top-progressbar2">
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Scheduling</p>
					</div>
				</div>
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Cancelled<br></p>
					</div>
				</div>
			</div>
		</div>
	@else
		<div class="card">
			<div class="row d-flex justify-content-between top-progressbar3">
				<div class="d-flex pb-4">
					<h5>Delegation Status</h5>
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<div class="col-12">
					<ul id="progressbar3" class="text-center">
						<li class="@if($delegation->status >= 0) active @endif step0" @if($delegation->status >= 0) style="cursor: pointer;" data-toggle="modal" data-target="#scheduling-modal" @endif></li>
						<li class="@if($delegation->status >= 1) active @endif step0" @if($delegation->status >= 1) style="cursor: pointer;" data-toggle="modal" data-target="#ongoing-modal" @endif></li>
						<li class="@if($delegation->status >= 4) active @endif step0" @if($delegation->status >= 4) style="cursor: pointer;" data-toggle="modal" data-target="#cancelled-during-delegation-modal" @endif></li>
					</ul>
				</div>
			</div>

			<div class="row justify-content-between top-progressbar3">
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Scheduling</p>
					</div>
				</div>
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Ongoing</p>
					</div>
				</div>
				<div class="row d-flex icon-content">
					<div class="d-flex flex-column">
						<p class="font-weight-bold">Cancelled During <br> Delegation</p>
					</div>
				</div>
			</div>
		</div>
	@endif

	{{-- Delegation Modals --}}
	<div class="modal fade" id="scheduling-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Delegation Scheduling</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This delegation is scheduling. <br>
						An email notification will be sent for the delegate manager when the delegation is ongoing.
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="ongoing-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Delegation Ongoing</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This delegation is ongoing. <br>
						Delegate manager will have the right to approve all requests made by the employees under the approval manager
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="completed-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Delegation Completed</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This delegation is completed.
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="cancelled-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Delegation Cancelled</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This delegation is cancelled by the manager.
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="cancelled-during-delegation-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Delegation Cancelled During Delegation</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p class="text-justify">
						This delegation is cancelled by the manager during delegation.
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	{{-- Delegation Details Table --}}
    <div class="pd-20 card-box mb-30">
        <div class="clearfix mb-20">
            <div class="pull-left">
                <h4 class="text-blue h4">Delegation's Details</h4>
            </div>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col" width="30%">Delegation's Details</th>
                    <th scope="col" width="70%">Delegation's Information</th>
                </tr>
            </thead>
            <tbody>
				<tr>
					<td class="font-weight-bold">Manager</td>
					<td>{{ $delegation->getManager->getFullName() }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Delegate Manager</td>
					<td>{{ $delegation->getDelegateManager->getFullName() }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Reason</td>
					<td>{{ $delegation->reason }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Start Date</td>
					<td>{{ date("d F Y", strtotime($delegation->startDate)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">End Date</td>
					<td>{{ date("d F Y", strtotime($delegation->endDate)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Status</td>
					<td>{{ $delegation->getStatus() }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Approval Delegation's Created Date & Time</td>
					<td>{{ date("d F Y, g:ia", strtotime($delegation->created_at)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Approval Delegation's Updated Date & Time</td>
					<td>{{ date("d F Y, g:ia", strtotime($delegation->updated_at)) }}</td>
				</tr>
				
            </tbody>
        </table>
    </div>
@endsection
