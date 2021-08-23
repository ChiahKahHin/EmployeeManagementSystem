@extends('layouts.template')

@section('title')
	{{ Auth::user()->getRoleName() }} | View Memo
@endsection

@section('pageTitle')
	View Memo
@endsection

@section('content')
	<div class="pd-20 card-box mb-30">
		<div class="clearfix mb-20">
			<div class="pull-left">
				<h4 class="text-blue h4">Memo Details</h4>
			</div>
		</div>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th scope="col" width="30%">Memo Details</th>
					<th scope="col" width="70%">Memo Information</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="font-weight-bold">Title</td>
					<td>{{ ucwords($memo->memoTitle) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Description</td>
					<td>{{ ucfirst($memo->memoDescription) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Date</td>
					<td>{{ date("d F Y", strtotime($memo->memoDate)) }}</td>
				</tr>
				@php
					$recipient = null;
					if($memo->memoRecipient == 0){
						$recipient = "All Employees";
					}
					else{
						$recipient = $memo->getDepartmentName() . " Department";
					}
				@endphp
				<tr>
					<td class="font-weight-bold">Recipient</td>
					<td>{{ ucfirst($recipient) }}</td>
				</tr>
				
				<tr>
					<td class="font-weight-bold">Status</td>
					<td>{{ $memo->getStatus() }}</td>
				</tr>
				@if ($memo->memoStatus == 0)
					<tr>
						<td class="font-weight-bold">Scheduled Date & Time</td>
						<td>{{ date("d F Y, g:ia", strtotime($memo->memoScheduled)) }}</td>
						
					</tr>
				@endif
				
				<tr>
					<td class="font-weight-bold">Memo Created Date & Time</td>
					<td>{{ date("d F Y, g:ia", strtotime($memo->created_at)) }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Memo Updated Date & Time</td>
					<td>{{ date("d F Y, g:ia", strtotime($memo->updated_at)) }}</td>
				</tr>
			</tbody>
		</table>
		@if ($memo->memoStatus == 0)
			<div class="row">
				<div class="col-md-12">
					<a class="btn btn-primary btn-block" href="{{ route('editMemo', ['id' => $memo->id]) }}">Edit Memorandum</a>
				</div>
			</div>
		@endif
	</div>
@endsection
