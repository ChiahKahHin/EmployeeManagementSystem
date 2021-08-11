@extends("layouts.template")

@section('title')
    EMS | Edit Employee
@endsection

@section('pageTitle')
    Edit Employee
@endsection

@section('content')
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left mb-10">
                <h4 class="text-blue h4">Edit Employee</h4>
            </div>
        </div>

        <form action="{{ route('editEmployee', ['id' => $employees->id]) }}" method="POST">
			@csrf
			<div class="clearfix">
				<hr>
					<div class="text-center mb-10">
						<h4 class="text-blue h5">Personal Info</h4>
					</div>
				<hr>
			</div>
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

			<div class="clearfix">
				<hr>
					<div class="text-center mb-10">
						<h4 class="text-blue h5">Create New Account</h4>
					</div>
				<hr>
			</div>

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

                    <div class="col-md-6">
                        <label>Email</label>
                        <input class="form-control @error('email') form-control-danger @enderror" type="email" name="email" autocomplete="off" placeholder="Enter email address (e.g. employee@gmail.com)" value="{{ old('email', $employees->email) }}">

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
                        <label>Employee ID</label>
                        <input class="form-control @error('employeeID') form-control-danger @enderror" type="text" name="employeeID" placeholder="Enter employee ID (employee ID must be unique)" value="{{ old('employeeID', $employees->employeeID) }}">

						@error("employeeID")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
                    </div>
					
					<div class="col-md-6">
						<label>Department</label>
						
						<select class="form-control @error('department') form-control-danger @enderror" id="department" name="department" onchange="checkDepartment();">
							<option value="" selected disabled hidden>Select department</option>
							
							@foreach ($departments as $department)
								<option value="{{ $department->id }}" data-departmentName="{{ $department->departmentName }}" {{ (old('department', $employees->department) == $department->id ? "selected": null) }}>{{ $department->departmentName }}</option>
							@endforeach
						</select>
						
						@error("department")
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
						<label>Manager Role? <i>(Optional)</i></label>
						<div class="custom-control custom-radio mb-5">
							<input type="radio" id="manager" name="manager" value="1" class="custom-control-input">
							<label class="custom-control-label" for="manager">Human Resource Manager</label>
						</div>
						<div class="custom-control custom-radio mb-5">
							<input type="radio" id="manager1" name="manager" value="2" class="custom-control-input">
							<label class="custom-control-label" for="manager1">Other Department Manager</label>
						</div>
					</div>
				</div>
			</div>

			<div class="row pt-10">
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary btn-block">Edit Employee</button>
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

@section("script")
	<script>
		$(document).ready(function() {
			countWords();
			@if (old('department', $employees->department))
				checkDepartment();
			@endif
		});

		function countWords(){
			var words = document.getElementById('address');
			$('#address_word_count').text(words.value.length + "/" + words.maxLength);
		};

		var check = true;
		
		function checkDepartment(){
			var department = document.getElementById('department');
			var departmentName = department.options[department.selectedIndex].getAttribute('data-departmentName');

			if(departmentName.toUpperCase() != "human resource".toUpperCase()){
				document.getElementById('manager').checked = false;
				document.getElementById('manager').disabled = true;
				document.getElementById('manager1').checked = false;
				document.getElementById('manager1').disabled = false;
			}
			else{
				document.getElementById('manager').checked = false;
				document.getElementById('manager').disabled = false;
				document.getElementById('manager1').checked = false;
				document.getElementById('manager1').disabled = true;
			}
			if(check){
				@if (old('manager', $employees->role))
					@if (old('manager', $employees->role) == 1)
						document.getElementById('manager').checked = true;
					@elseif (old('manager', $employees->role) == 2)
						document.getElementById('manager1').checked = true;	
					@else
						document.getElementById('manager').checked = false;
						document.getElementById('manager1').checked = false;	
					@endif
				@endif
				check = false;
			}
		}
	</script>
@endsection