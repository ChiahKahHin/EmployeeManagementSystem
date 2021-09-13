@extends("layouts.template")

@section('title')
    {{ Auth::user()->getRoleName() }} | Edit Employee
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
						<h4 class="text-blue h5">Personal Information</h4>
					</div>
				<hr>
			</div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>First Name</label>
                        <input class="form-control @error('firstname') form-control-danger @enderror" type="text" name="firstname" placeholder="Enter first name" value="{{ old('firstname', $employees->firstname) }}" required>
						
						@error("firstname")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
					</div>

					<div class="col-md-6">
                        <label>Last Name</label>
                        <input class="form-control @error('lastname') form-control-danger @enderror" type="text" name="lastname" placeholder="Enter last name" value="{{ old('lastname', $employees->lastname) }}" required>
						
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
                        <input class="form-control @error('contactNumber') form-control-danger @enderror" type="text" name="contactNumber" placeholder="Enter contact number (e.g. 012-3456789)" value="{{ old('contactNumber', $employees->contactNumber) }}" required>

						@error("contactNumber")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
                    </div>

					<div class="col-md-6">
                        <label>Date of Birth</label>
                        <input class="form-control @error('dateOfBirth') form-control-danger @enderror" type="date" max="@php echo date("Y-m-d") @endphp" name="dateOfBirth" placeholder="Select date of birth" value="{{ old('dateOfBirth', $employees->dateOfBirth) }}" required>
						
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
						<label>Home Address</label>
						<textarea class="form-control @error('address') form-control-danger @enderror" id="address" name="address" style="min-height:50px; max-height:150px; height:45px; resize: vertical;" placeholder="Enter home address" maxlength="255" onkeyup="countWords()" data-gramm_editor="false" required>{{ old('address', $employees->address) }}</textarea>
						
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
						<label>NRIC/Passport No</label>
						<input class="form-control @error('ic') form-control-danger @enderror" type="text" minlength="12" maxlength="12" name="ic" placeholder="Enter NRIC/Passport No" value="{{ old('ic', $employees->ic) }}" required>

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
								<option value="{{ $nationality }}" {{ (old('nationality', $employees->nationality) == $nationality ? "selected": null) }}>{{ $nationality }}</option>
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
								<option value="{{ $citizenship }}" {{ (old('citizenship', $employees->citizenship) == $citizenship ? "selected": null) }}>{{ $citizenship }}</option>
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
								<option value="{{ $religion }}" {{ (old('religion', $employees->religion) == $religion ? "selected": null) }}>{{ $religion }}</option>
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
								<option value="{{ $race }}" {{ (old('race', $employees->race) == $race ? "selected": null) }}>{{ $race }}</option>
							@endforeach
						</select>
						
						@error("race")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror	
                    </div>

					<div class="col-md-6">
						<label>Marital Status</label>
						@php
							$maritalStatuses = array("Single", "Married", "Divorce", "Widowed");
						@endphp
						<select class="form-control selectpicker @error('maritalStatus') form-control-danger @enderror" id="maritalStatus" name="maritalStatus" onchange="checkMaritalStatus();" required>
							<option value="" selected disabled hidden>Select marital status</option>
							@foreach ($maritalStatuses as $maritalStatus)
								<option value="{{ $maritalStatus }}" {{ (old('maritalStatus', $employees->maritalStatus) == $maritalStatus ? "selected": null) }}>{{ $maritalStatus }}</option>
							@endforeach
						</select>
						
						@error("maritalStatus")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror	
                    </div>
                </div>
            </div>

			<div id="showSpouseInformation" style="display: none;">
				<div class="clearfix">
					<hr>
						<div class="text-center mb-10">
							<h4 class="text-blue h5">Spouse Information</h4>
						</div>
					<hr>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<label>Spouse Name</label>
							<input class="form-control @error('spouseName') form-control-danger @enderror" type="text" id="spouseName" name="spouseName" placeholder="Enter spouse name" value="{{ old('spouseName', $employees->spouseName) }}">
							
							@error("spouseName")
								<div class="text-danger text-sm">
									{{ $message }}
								</div>
							@enderror
						</div>
	
						<div class="col-md-6">
							<label>Spouse Date of Birth</label>
							<input class="form-control @error('spouseDateOfBirth') form-control-danger @enderror" type="date" max="@php echo date("Y-m-d") @endphp" id="spouseDateOfBirth" name="spouseDateOfBirth" placeholder="Select spouse date of birth" value="{{ old('spouseDateOfBirth', $employees->spouseDateOfBirth) }}">
							
							@error("spouseDateOfBirth")
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
							<label>Spouse NRIC/Passport No</label>
							<input class="form-control @error('spouseIC') form-control-danger @enderror" type="text" id="spouseIC" name="spouseIC" placeholder="Enter spouse NRIC/Passport No" value="{{ old('spouseIC', $employees->spouseIC) }}">
	
							@error("spouseIC")
								<div class="text-danger text-sm">
									{{ $message }}
								</div>
							@enderror
						</div>

						<div class="col-md-6">
							<label>Date of Marriage</label>
							<input class="form-control @error('dateOfMarriage') form-control-danger @enderror" type="date" max="@php echo date("Y-m-d") @endphp" id="dateOfMarriage" name="dateOfMarriage" placeholder="Select spouse date of marriage" value="{{ old('dateOfMarriage', $employees->dateOfMarriage) }}">
							
							@error("dateOfMarriage")
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
							<label>Spouse Occupation</label>
							<input class="form-control @error('spouseOccupation') form-control-danger @enderror" type="text" id="spouseOccupation" name="spouseOccupation" placeholder="Enter spouse occupation" value="{{ old('spouseOccupation', $employees->spouseOccupation) }}">
	
							@error("spouseOccupation")
								<div class="text-danger text-sm">
									{{ $message }}
								</div>
							@enderror
						</div>

						<div class="col-md-6">
							<label>Spouse Contact Number</label>
							<input class="form-control @error('spouseContactNumber') form-control-danger @enderror" type="text" id="spouseContactNumber" name="spouseContactNumber" placeholder="Enter spouse contact number (e.g. 012-3456789)" value="{{ old('spouseContactNumber', $employees->spouseContactNumber) }}">
	
							@error("spouseContactNumber")
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
							<label>Spouse Resident Status</label>
							@php
								$spouseResidentStatuses = array("Resident", "Non-Resident", "Local Citizen", "Foreigner");
							@endphp
							<select class="form-control selectpicker @error('spouseResidentStatus') form-control-danger @enderror" id="spouseResidentStatus" name="spouseResidentStatus">
								<option value="" selected disabled hidden>Select spouse resident status</option>
								@foreach ($spouseResidentStatuses as $spouseResidentStatus)
									<option value="{{ $spouseResidentStatus }}" {{ (old('spouseResidentStatus', $employees->spouseResidentStatus) == $spouseResidentStatus ? "selected": null) }}>{{ $spouseResidentStatus }}</option>
								@endforeach
							</select>
							
							@error("spouseResidentStatus")
								<div class="text-danger text-sm">
									{{ $message }}
								</div>
							@enderror	
						</div>
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
                        <input class="form-control @error('emergencyContactName') form-control-danger @enderror" type="text" name="emergencyContactName" placeholder="Enter emergency contact name" value="{{ old('emergencyContactName', $employees->emergencyContactName) }}" required>

						@error("emergencyContactName")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
                    </div>

					<div class="col-md-6">
                        <label>Emergency Contact Number</label>
                        <input class="form-control @error('emergencyContactNumber') form-control-danger @enderror" type="text" name="emergencyContactNumber" placeholder="Enter emergency contact number (e.g. 012-3456789)" value="{{ old('emergencyContactNumber', $employees->emergencyContactNumber) }}" required>

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
                        <label>Emergency Address</label>
                        <textarea class="form-control @error('emergencyAddress') form-control-danger @enderror" id="emergencyAddress" name="emergencyAddress" style="min-height:50px; max-height:150px; height:45px; resize: vertical;" placeholder="Enter emergency address" maxlength="255" onkeyup="countWords1()" data-gramm_editor="false" required>{{ old('emergencyAddress', $employees->emergencyContactAddress) }}</textarea>
						
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
                        <input class="form-control @error('username') form-control-danger @enderror" type="text" name="username" placeholder="Enter username (username must be unique)" value="{{ old('username', $employees->username) }}" required>

						@error("username")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
                    </div>

                    <div class="col-md-6">
                        <label>Email</label>
                        <input class="form-control @error('email') form-control-danger @enderror" type="email" name="email" autocomplete="off" placeholder="Enter email address (e.g. employee@gmail.com)" value="{{ old('email', $employees->email) }}" required>

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
                        <input class="form-control @error('employeeID') form-control-danger @enderror" type="text" name="employeeID" placeholder="Enter employee ID (employee ID must be unique)" value="{{ old('employeeID', $employees->employeeID) }}" required>

						@error("employeeID")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror
                    </div>

					<div class="col-md-6">
						<label>Department</label>
						
						<select class="form-control selectpicker @error('department') form-control-danger @enderror" id="department" name="department" required>
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
						<label>Position</label>
						
						<select class="form-control selectpicker @error('position') form-control-danger @enderror" id="position" name="position" required>
							<option value="" selected disabled hidden>Select position</option>
							
							@foreach ($positions as $position)
								<option value="{{ $position->id }}" {{ (old('position', $employees->position) == $position->id ? "selected": null) }}> {{ $position->positionName }}</option>
							@endforeach
						</select>
						
						@error("position")
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
								<option value="{{ $manager->id }}" {{ (old('reportingManager', $employees->reportingManager) == $manager->id ? "selected": null) }}>{{ $manager->getFullName() }} ({{ $manager->getDepartment->departmentName }})</option>
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
						<label>System Role</label>
						@php
							$roles = array(0 => "Super Admin", 2 => "Manager", 3 => "Employee");
						@endphp
						<select class="form-control selectpicker @error('role') form-control-danger @enderror" name="role" required>
							<option value="" selected disabled hidden>Select system role</option>
							@foreach ($roles as $key => $role)
								<option value="{{ $key }}" {{ (((old('role', $employees->role) == $key && !is_null(old('role', $employees->role))) || $employees->role == 1 && $key == 2) ? "selected" : "") }}>{{ $role }}</option>
							@endforeach
						</select>
						
						@error("role")
							<div class="text-danger text-sm">
								{{ $message }}
							</div>
						@enderror	
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
				//timer:5000
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
			checkMaritalStatus();
		});

		function checkMaritalStatus(){
			var maritalStatus = document.getElementById('maritalStatus').value;
			if(maritalStatus == "Married"){
				document.getElementById('showSpouseInformation').removeAttribute('style');
				document.getElementById('spouseName').setAttribute('required', '');
				document.getElementById('spouseDateOfBirth').setAttribute('required', '');
				document.getElementById('spouseIC').setAttribute('required', '');
				document.getElementById('dateOfMarriage').setAttribute('required', '');
				document.getElementById('spouseOccupation').setAttribute('required', '');
				document.getElementById('spouseContactNumber').setAttribute('required', '');
				document.getElementById('spouseResidentStatus').setAttribute('required', '');
			}
			else{
				document.getElementById('showSpouseInformation').setAttribute('style', 'display:none;');
				document.getElementById('spouseName').removeAttribute('required');
				document.getElementById('spouseDateOfBirth').removeAttribute('required');
				document.getElementById('spouseIC').removeAttribute('required');
				document.getElementById('dateOfMarriage').removeAttribute('required');
				document.getElementById('spouseOccupation').removeAttribute('required');
				document.getElementById('spouseContactNumber').removeAttribute('required');
				document.getElementById('spouseResidentStatus').removeAttribute('required');
			}
		}

		function countWords(){
			var words = document.getElementById('address');
			$('#address_word_count').text(words.value.length + "/" + words.maxLength);
		}

		function countWords2(){
			var words = document.getElementById('emergencyAddress');
			$('#emergency_address_word_count').text(words.value.length + "/" + words.maxLength);
		}
	</script>
@endsection