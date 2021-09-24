<div class="left-side-bar">
	<div>
		<ul>
			<li>
				<div class="sidebar-small-cap" style="text-align: center; padding: 1.25rem 0 1rem 0;">
					<a href="@if (Auth::user()->isAccess('admin', 'hrmanager'))
								{{ route('dashboard1') }}
							@elseif (Auth::user()->isAccess('manager', 'employee'))
								{{ route('dashboard2') }}
							@endif" 
					style="color:white;">Emp. Management System</a>
				</div>
			</li>
			<li>
				<div class="dropdown-divider"></div>
			</li>
		</ul>
	</div>

	<div class="menu-block customscroll">
		<div class="sidebar-menu">
			<ul id="accordion-menu">
				<li>
					<a href="@if (Auth::user()->isAccess('admin', 'hrmanager'))
								{{ route('dashboard1') }}
							@elseif (Auth::user()->isAccess('manager', 'employee'))
								{{ route('dashboard2') }}
							@endif" 
					
						class="dropdown-toggle no-arrow">
						<span class="micon dw dw-house-1"></span><span class="mtext">Home</span>
					</a>
				</li>

				@if (Auth::user()->isAccess('admin', 'hrmanager'))
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-group"></span><span class="mtext">Department</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('addDepartment') }}">Add Department</a></li>
							<li><a href="{{ route('manageDepartment') }}">Manage Department</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-add-user"></span><span class="mtext">Employee</span>
						</a>
						<ul class="submenu">
							<li class="dropdown">
								<a href="javascript:;" class="dropdown-toggle">
									<span class="micon dw dw-list pl-2"></span><span class="mtext">Position</span>
								</a>
								<ul class="submenu child">
									<li><a href="{{ route('addPosition') }}">Add Position</a></li>
									<li><a href="{{ route('managePosition') }}">Manage Position</a></li>
								</ul>
							</li>
							<li><a href="{{ route('addEmployee') }}">Add Employee</a></li>
							<li><a href="{{ route('manageEmployee') }}">Manage Employee</a></li>
						</ul>
					</li>
					
				@endif
				
				@php
					$count = DB::select('select count(reportingManager) as count from users where reportingManager = '.Auth::id().'');
				@endphp
				@if ($count[0]->count > 0)
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-user3"></span><span class="mtext">Approval Delegation</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('addDelegation') }}">Add Delegation</a></li>
							<li><a href="{{ route('manageDelegation') }}">Manage Delegation</a></li>
						</ul>
					</li>
				@endif

				@if (Auth::user()->isAccess('admin', 'hrmanager'))
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-notepad-2"></span><span class="mtext">Memorandum</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('createMemo') }}">Create Memorandum</a></li>
							<li><a href="{{ route('manageMemo') }}">Manage Memorandum</a></li>
						</ul>
					</li>
				@endif

				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon dw dw-invoice-1"></span><span class="mtext">Task</span>
					</a>
					<ul class="submenu">
						@if (Auth::user()->isAccess('admin','hrmanager','manager'))
							<li><a href="{{ route('addTask') }}">Add Task</a></li>
						@endif
						<li><a href="{{ route('manageTask') }}">Manage Task</a></li>
					</ul>
				</li>


				@if (Auth::user()->isAccess('admin', 'hrmanager'))
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-calendar1"></span><span class="mtext">Leave</span>
						</a>
						<ul class="submenu">
							<li class="dropdown">
								<a href="javascript:;" class="dropdown-toggle">
									<span class="micon dw dw-list pl-2"></span><span class="mtext">Public Holiday</span>
								</a>
								<ul class="submenu child">
									<li><a href="{{ route('addPublicHoliday') }}">Add Public Holiday</a></li>
									<li><a href="{{ route('managePublicHoliday') }}">Manage Public Holiday</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="javascript:;" class="dropdown-toggle">
									<span class="micon dw dw-list pl-2"></span><span class="mtext">Leave Type</span>
								</a>
								<ul class="submenu child">
									<li><a href="{{ route('addLeaveType') }}">Add Leave Type</a></li>
									<li><a href="{{ route('manageLeaveType') }}">Manage Leave Type</a></li>
								</ul>
							</li>
							<li><a href="{{ route('manageWorkingDay') }}">Manage Working Day</a></li>
							<li><a href="{{ route('leaveCalendar') }}">Leave Calendar</a></li>
							<li><a href="{{ route('viewLeaveBalance') }}">Leave Balance</a></li>
							<li><a href="{{ route('applyLeave') }}">Apply Leave</a></li>
							<li><a href="{{ route('manageLeave') }}">Manage Leave Requests</a></li>
						</ul>
					</li>
				@endif

				@if (Auth::user()->isAccess('manager', 'employee'))
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-calendar1"></span><span class="mtext">Leave</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('leaveCalendar') }}">Leave Calendar</a></li>
							<li><a href="{{ route('viewLeaveBalance') }}">Leave Balance</a></li>
							<li><a href="{{ route('applyLeave') }}">Apply Leave</a></li>
							<li><a href="{{ route('manageLeave') }}">Manage Leave</a></li>
						</ul>
					</li>
				@endif

				@if (Auth::user()->isAccess('admin', 'hrmanager'))
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-invoice"></span><span class="mtext">Benefit Claim</span>
						</a>
						<ul class="submenu">
							<li class="dropdown">
								<a href="javascript:;" class="dropdown-toggle">
									<span class="micon dw dw-list pl-2"></span><span class="mtext">Claim Category</span>
								</a>
								<ul class="submenu child">
									<li><a href="{{ route('addClaimCategory') }}">Add Claim Category</a></li>
									<li><a href="{{ route('manageClaimCategory') }}">Manage Claim Category</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="javascript:;" class="dropdown-toggle">
									<span class="micon dw dw-list pl-2"></span><span class="mtext">Claim Type</span>
								</a>
								<ul class="submenu child">
									<li><a href="{{ route('addClaimType') }}">Add Claim Type</a></li>
									<li><a href="{{ route('manageClaimType') }}">Manage Claim Type</a></li>
								</ul>
							</li>
							<li><a href="{{ route('applyBenefitClaim') }}">Apply Benefit Claim</a></li>
							<li><a href="{{ route('manageClaimRequest') }}">Manage Claim Request</a></li>
						</ul>
					</li>
				@endif

				@if (Auth::user()->isAccess('manager', 'employee'))
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-invoice"></span><span class="mtext">Benefit Claim</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('applyBenefitClaim') }}">Apply Benefit Claim</a></li>
							<li><a href="{{ route('manageClaimRequest') }}">Manage Claim Request</a></li>
						</ul>
					</li>
				@endif

				@if (Auth::user()->isAccess('admin', 'hrmanager'))
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-presentation-2"></span><span class="mtext">Training Program</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('addTrainingProgram') }}">Add Training Program</a></li>
							<li><a href="{{ route('manageTrainingProgram') }}">Manage Training Program</a></li>
						</ul>
					</li>
				@endif

				@if (Auth::user()->isAccess('manager', 'employee'))
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-presentation-2"></span><span class="mtext">Training Program</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('manageTrainingProgram') }}">Manage Training Program</a></li>
						</ul>
					</li>
				@endif

				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon dw dw-analytics1"></span><span class="mtext">Analytics</span>
					</a>
					<ul class="submenu">
						@if (Auth::user()->isAdmin())
							<li><a href="{{ route('taskAnalyticsPage') }}">Task Analytics</a></li>
						@elseif(Auth::user()->isAccess('hrmanager', 'manager'))
							<li><a href="{{ route('taskAnalyticsPage2') }}">Task Analytics</a></li>
						@else
							<li><a href="{{ route('taskAnalyticsPage3') }}">Task Analytics</a></li>
						@endif

						@if(Auth::user()->isAdmin())
							<li><a href="{{ route('leaveAnalytics') }}">Leave Analytics</a></li>
						@elseif (Auth::user()->isAccess('hrmanager', 'manager'))
							<li><a href="{{ route('leaveAnalytics2') }}">Leave Analytics</a></li>
						@else
							<li><a href="{{ route('leaveAnalytics3') }}">Leave Analytics</a></li>
						@endif
						
						@if(Auth::user()->isAdmin())
							<li><a href="{{ route('claimAnalytics') }}">Benefit Claim Analytics</a></li>
						@elseif (Auth::user()->isAccess('hrmanager', 'manager'))
							<li><a href="{{ route('claimAnalytics2') }}">Benefit Claim Analytics</a></li>
						@else
							<li><a href="{{ route('claimAnalytics3') }}">Benefit Claim Analytics</a></li>
						@endif

						@if (Auth::user()->isAdmin())
							<li><a href="{{ route('trainingAnaytics') }}">Training Program Analytics</a></li>
						@else
							<li><a href="{{ route('trainingAnaytics2') }}">Training Program Analytics</a></li>
						@endif
					</ul>
				</li>

				<li>
					<div class="dropdown-divider"></div>
				</li>
				{{-- <li>
					<div class="sidebar-small-cap">Section Divider</div>
				</li> --}}
			</ul>
		</div>
	</div>
</div>