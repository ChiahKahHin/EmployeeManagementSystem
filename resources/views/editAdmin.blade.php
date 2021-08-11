@extends("layouts.template")

@section('title')
    {{ Auth::user()->getRoleName() }} | Edit Admin
@endsection

@section('pageTitle')
    Edit Admin
@endsection

@section('content')
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left mb-10">
                <h4 class="text-blue h4">Edit Admin</h4>
            </div>
        </div>

        <form action="{{ route('editAdmin', ['id' => $admins->id]) }}" method="POST">
			@csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>First Name</label>
                        <input class="form-control @error('firstname') form-control-danger @enderror" type="text" name="firstname" placeholder="Enter first name" value="{{ old('firstname', $admins->firstname) }}">
						
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
                        <input class="form-control @error('lastname') form-control-danger @enderror" type="text" name="lastname" placeholder="Enter last name" value="{{ old('lastname', $admins->lastname) }}">
						
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
                        <input class="form-control @error('contactNumber') form-control-danger @enderror" type="text" name="contactNumber" placeholder="Enter contact number (e.g. 012-3456789)" value="{{ old('contactNumber', $admins->contactNumber) }}">

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
                        <input class="form-control date-picker @error('dateOfBirth') form-control-danger @enderror" type="text" name="dateOfBirth" placeholder="Select date of birth" value="{{ old('dateOfBirth', date("d F Y", strtotime($admins->dateOfBirth))) }}">
						
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
								<option value="{{ $gender }}" {{ (old('gender', $admins->gender) == $gender ? "selected": null) }}>{{ ucfirst($gender) }}</option>
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

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Email</label>
                        <input class="form-control @error('email') form-control-danger @enderror" type="email" name="email" autocomplete="off" placeholder="Enter email address (e.g. admin@gmail.com)" value="{{ old('email', $admins->email) }}">

						@error("email")
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
                        <label>Username</label>
                        <input class="form-control @error('username') form-control-danger @enderror" type="text" name="username" placeholder="Enter username (username must be unique)" value="{{ old('username', $admins->username) }}">

						@error("username")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
                    </div>
                </div>
            </div>

			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary btn-block">Edit Admin</button>
				</div>
			</div>
        </form>
    </div>
	@if (session('message'))
		<script>
			swal({
				title: '{{ session("message") }}',
				type: 'success',
				confirmButtonClass: 'btn btn-success',
				timer: 5000
			});
		</script>
	@endif
@endsection