@extends("layouts.template")

@section('title')
    {{ Auth::user()->getRoleName() }} | Add Employee
@endsection

@section('pageTitle')
    Add Employee
@endsection

@section('content')
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left mb-10">
                <h4 class="text-blue h4">Add Employee</h4>
            </div>
        </div>
		@if (count($departments) == 0)
			<script>
				swal({
					title: 'Warning',
					html: 'There is no department added at the moment<br> Please inform the Human Resource Department',
					type: 'warning',
					confirmButtonClass: 'btn btn-danger',
				}).then(function(){
					window.location.href = "/";
				});
			</script>
		@endif
		@if (count($managers) == 0)
			<script>
				swal({
					title: 'Warning',
					html: 'There is no reporting managers added at the moment<br> Please inform the Human Resource Department',
					type: 'warning',
					confirmButtonClass: 'btn btn-danger',
				}).then(function(){
					window.location.href = "/";
				});
			</script>
		@endif
        <form action="{{ route('addEmployee') }}" method="POST">
			@csrf
			<div class="clearfix">
				<hr>
					<div class="text-center mb-10">
						<h4 class="text-blue h5">Personal Information</h4>
					</div>
				<hr>
			</div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>First Name</label>
                        <input class="form-control @error('firstname') form-control-danger @enderror" type="text" name="firstname" placeholder="Enter first name" value="{{ old('firstname') }}" required>
						
						@error("firstname")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>

					<div class="col-md-6">
                        <label>Last Name</label>
                        <input class="form-control @error('lastname') form-control-danger @enderror" type="text" name="lastname" placeholder="Enter last name" value="{{ old('lastname') }}" required>
						
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
                        <input class="form-control @error('contactNumber') form-control-danger @enderror" type="text" name="contactNumber" placeholder="Enter contact number (e.g. 012-3456789)" value="{{ old('contactNumber') }}" required>

						@error("contactNumber")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
                    </div>

					<div class="col-md-6">
                        <label>Date of Birth</label>
                        <input class="form-control @error('dateOfBirth') form-control-danger @enderror" type="date" max="@php echo date("Y-m-d") @endphp" name="dateOfBirth" placeholder="Select date of birth" value="{{ old('dateOfBirth') }}" required>
						
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
							$genders = array("Male", "Female");
						@endphp
						<select class="form-control selectpicker @error('gender') form-control-danger @enderror" name="gender" required>
							<option value="" selected disabled hidden>Select gender</option>
							@foreach ($genders as $gender)
								<option value="{{ $gender }}" {{ (old('gender') == $gender ? "selected": null) }}>{{ ucfirst($gender) }}</option>
							@endforeach
						</select>
						
						@error("gender")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror	
                    </div>

					<div class="col-md-6">
						<label>Home Address</label>
						<textarea class="form-control @error('address') form-control-danger @enderror" id="address" name="address" style="min-height:50px; max-height:150px; height:45px; resize: vertical;" placeholder="Enter home address" maxlength="255" onkeyup="countWords()" data-gramm_editor="false" required>{{ old('address') }}</textarea>
						
						<div id="address_word_count" class="text-sm" style="text-align: right"></div>
						
						@error("address")
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
						<label>NRIC</label>
						<input class="form-control @error('ic') form-control-danger @enderror" type="text" minlength="12" maxlength="12" name="ic" placeholder="Enter IC Number (without '-')" value="{{ old('ic') }}" required>

						@error("ic")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>

                    <div class="col-md-6">
						<label>Nationality</label>
						<select class="form-control custom-select2 nationality @error('nationality') form-control-danger @enderror" name="nationality" required>
							@foreach ($nationalities as $nationality)
								<option value="{{ $nationality }}" {{ (old('nationality') == $nationality ? "selected": null) }} @if ($nationality == "Malaysian") selected @endif>{{ $nationality }}</option>
							@endforeach
						</select>
						
						@error("nationality")
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
						<label>Citizenship</label>
						@php
							$citizenships = array("Malaysian Citizen", "Malaysian Permanent Resident", "Malaysian Temporary Resident", "Other");
						@endphp
						<select class="form-control selectpicker @error('citizenship') form-control-danger @enderror" name="citizenship" required>
							<option value="" selected disabled hidden>Select citizenship</option>
							@foreach ($citizenships as $citizenship)
								<option value="{{ $citizenship }}" {{ (old('citizenship') == $citizenship ? "selected": null) }}>{{ $citizenship }}</option>
							@endforeach
						</select>
						
						@error("citizenship")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror	
                    </div>

					<div class="col-md-6">
						<label>Religion</label>
						@php
							$religions = array("Buddist", "Christian", "Hindu", "Muslim", "Other");
						@endphp
						<select class="form-control selectpicker @error('religion') form-control-danger @enderror" name="religion" required>
							<option value="" selected disabled hidden>Select religion</option>
							@foreach ($religions as $religion)
								<option value="{{ $religion }}" {{ (old('religion') == $religion ? "selected": null) }}>{{ $religion }}</option>
							@endforeach
						</select>
						
						@error("religion")
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
						<label>Race</label>
						@php
							$races = array("Chinese", "Malay", "Indian", "Other");
						@endphp
						<select class="form-control selectpicker @error('race') form-control-danger @enderror" name="race" required>
							<option value="" selected disabled hidden>Select race</option>
							@foreach ($races as $race)
								<option value="{{ $race }}" {{ (old('race') == $race ? "selected": null) }}>{{ $race }}</option>
							@endforeach
						</select>
						
						@error("race")
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
						<h4 class="text-blue h5">Emergency Contact</h4>
					</div>
				<hr>
			</div>

			<div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Emergency Contact Name</label>
                        <input class="form-control @error('emergencyContactName') form-control-danger @enderror" type="text" name="emergencyContactName" placeholder="Enter emergency contact name" value="{{ old('emergencyContactName') }}" required>

						@error("emergencyContactName")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
                    </div>

					<div class="col-md-6">
                        <label>Emergency Contact Number</label>
                        <input class="form-control @error('emergencyContactNumber') form-control-danger @enderror" type="text" name="emergencyContactNumber" placeholder="Enter emergency contact number (e.g. 012-3456789)" value="{{ old('emergencyContactNumber') }}" required>

						@error("emergencyContactNumber")
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
                        <label>Emergency Address <i>(Same as home address?)</i> <input class="form-control switch-btn" type="checkbox" id="getHomeAddress" name="getHomeAddress" onchange="getAddress();" data-size="small" data-color="#0099ff" {{ (old('getHomeAddress')? "checked": null) }}></label>
                        <textarea class="form-control @error('emergencyAddress') form-control-danger @enderror" id="emergencyAddress" name="emergencyAddress" style="min-height:50px; max-height:150px; height:45px; resize: vertical;" placeholder="Enter emergency address" maxlength="255" onkeyup="countWords1()" data-gramm_editor="false" required>{{ old('emergencyAddress') }}</textarea>
						
						<div id="emergency_address_word_count" class="text-sm" style="text-align: right"></div>
                    </div>
                </div>
            </div>

			<div class="clearfix">
				<hr>
					<div class="text-center mb-10">
						<h4 class="text-blue h5">Account Information</h4>
					</div>
				<hr>
			</div>

            <div class="form-group">
                <div class="row">
					<div class="col-md-6">
                        <label>Username</label>
                        <input class="form-control @error('username') form-control-danger @enderror" type="text" name="username" placeholder="Enter username (username must be unique)" value="{{ old('username') }}" required>

						@error("username")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
                    </div>

                    <div class="col-md-6">
                        <label>Email</label>
                        <input class="form-control @error('email') form-control-danger @enderror" type="email" name="email" autocomplete="off" placeholder="Enter email address (e.g. employee@gmail.com)" value="{{ old('email') }}" required>

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
                        <input class="form-control @error('employeeID') form-control-danger @enderror" type="text" name="employeeID" placeholder="Enter employee ID (employee ID must be unique)" value="{{ old('employeeID') }}" required>

						@error("employeeID")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
                    </div>

					<div class="col-md-6">
						<label>Reporting Manager</label>
						
						<select class="form-control selectpicker @error('reportingManager') form-control-danger @enderror" id="reportingManager" name="reportingManager" required>
							<option value="" selected disabled hidden>Select reporting manager</option>
							
							@foreach ($managers as $manager)
								<option value="{{ $manager->id }}" {{ (old('reportingManager') == $manager->id ? "selected": null) }}>{{ $manager->getFullName() }}</option>
							@endforeach
						</select>
						
						@error("reportingManager")
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
						<label>Department</label>
						
						<select class="form-control selectpicker @error('department') form-control-danger @enderror" id="department" name="department" onchange="checkDepartment();" required>
							<option value="" selected disabled hidden>Select department</option>
							
							@foreach ($departments as $department)
								<option value="{{ $department->id }}" data-departmentName="{{ $department->departmentName }}" {{ (old('department') == $department->id ? "selected": null) }}>{{ $department->departmentName }}</option>
							@endforeach
						</select>
						
						@error("department")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror	
                    </div>
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
					<button type="submit" class="btn btn-primary btn-block">Add Employee</button>
				</div>
			</div>
        </form>
    </div>
	@if (session('message'))
		<script>
			swal({
				title: '{{ session("message") }}',
				html: '@php echo session("message1") @endphp',
				type: 'success',
				confirmButtonClass: 'btn btn-success',
				//timer:7500
			});
		</script>
	@endif
@endsection

@section("script")
	<script>
		$(document).ready(function() {
			$('.nationality').select2({
				placeholder : "Select Nationality",
				allowClear: true
			});
			countWords();
			countWords2();
			@if (old('department'))
				checkDepartment();
			@endif
		});

		function getAddress(){
			var checked = document.getElementById('getHomeAddress').checked;
			var emergencyAddress = document.getElementById('emergencyAddress');
			if(checked ==  true){
				var homeAddress = document.getElementById('address').value;
				emergencyAddress.value = homeAddress;
			}
			else{
				emergencyAddress.value = "";
			}
			countWords2();
		}

		function countWords(){
			var words = document.getElementById('address');
			$('#address_word_count').text(words.value.length + "/" + words.maxLength);
		};

		function countWords2(){
			var words = document.getElementById('emergencyAddress');
			$('#emergency_address_word_count').text(words.value.length + "/" + words.maxLength);
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
				@if (old('manager'))
					@if (old('manager') == 1)
						document.getElementById('manager').checked = true;
					@else
						document.getElementById('manager1').checked = true;	
					@endif
				@endif
				check = false;
			}
		}
	</script>
@endsection