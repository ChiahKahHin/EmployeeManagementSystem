@extends('layouts.template')

@section('title')
	EMS | Update Profile
@endsection

@section('pageTitle')
	Update Profile
@endsection

@section('content')
	<div class="pd-20 card-box mb-30">
		<div class="clearfix">
			<div class="pull-left mb-10">
				<h4 class="text-blue h4">Update Profile</h4>
			</div>
		</div>

		<form action="{{ route('updateProfile') }}" method="POST">
			@csrf
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>First Name</label>
						<input class="form-control @error('firstname') form-control-danger @enderror" type="text" name="firstname" placeholder="Enter first name" value="{{ old('firstname', $employees->firstname) }}">
						
						@error("firstname")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Last Name</label>
						<input class="form-control @error('lastname') form-control-danger @enderror" type="text" name="lastname" placeholder="Enter last name" value="{{ old('lastname', $employees->lastname) }}">
						
						@error("lastname")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Contact Number</label>
						<input class="form-control @error('contactNumber') form-control-danger @enderror" type="text" name="contactNumber" placeholder="Enter contact number (e.g. 012-3456789)" value="{{ old('contactNumber', $employees->contactNumber) }}">

						@error("contactNumber")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Date of Birth</label>
						<input class="form-control date-picker @error('dateOfBirth') form-control-danger @enderror" type="text" name="dateOfBirth" placeholder="Select date of birth" value="{{ old('dateOfBirth', date("d F Y", strtotime($employees->dateOfBirth))) }}">
						
						@error("dateOfBirth")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Gender</label>
						@php
							$genders = array("male", "female");
						@endphp
						<select class="form-control @error('gender') form-control-danger @enderror" name="gender">
							<option value="" selected disabled hidden>Select gender</option>
							@foreach ($genders as $gender)
								<option value="{{ $gender }}" {{ (old('gender', $employees->gender) == $gender ? "selected": null) }}>{{ ucfirst($gender) }}</option>
							@endforeach
						</select>
						
						@error("gender")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror	
					</div>
				</div>
			</div>
			@if (!Auth::user()->isAdmin())
				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<label>Address</label>
							<textarea class="form-control @error('address') form-control-danger @enderror" id="address" name="address" style="min-height:50px; max-height:150px; height:45px; resize: vertical;" placeholder="Enter address" maxlength="255" onkeyup="countWords()" data-gramm_editor="false">{{ old('address', $employees->address) }}</textarea>
							
							<div id="address_word_count" class="text-sm" style="text-align: right"></div>
							
							@error("address")
								<div class="text-danger text-sm">
									{{ $message }}
								</div>
							@enderror
						</div>
					</div>
				</div>
			@endif

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Username</label>
						<input class="form-control @error('username') form-control-danger @enderror" type="text" name="username" placeholder="Enter username (username must be unique)" value="{{ old('username', $employees->username) }}">

						@error("username")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Email</label>
						<input class="form-control @error('email') form-control-danger @enderror" type="email" name="email" autocomplete="off" placeholder="Enter email address (e.g. admin@gmail.com)" value="{{ old('email', $employees->email) }}">

						@error("email")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>
				</div>
			</div>


			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Update Profile</button>
				</div>
			</div>
		</form>
	</div>
@endsection

@section("script")
	@if (!Auth::user()->isAdmin())
		<script>
			$(document).ready(function() {
				countWords();
			});

			function countWords(){
				var words = document.getElementById('address');
				$('#address_word_count').text(words.value.length + "/" + words.maxLength);
			};
		</script>
	@endif
@endsection
