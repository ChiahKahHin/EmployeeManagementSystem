@extends('layouts.template')

@section('title')
    {{ Auth::user()->getRoleName() }} | View Delegation Details
@endsection

@section('pageTitle')
    View Delegation Details
@endsection

@section('content')
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
