<div class="left-side-bar">
	<div>
		<ul>
			<li>
				<div class="sidebar-small-cap" style="text-align: center; padding: 1.25rem 0 1rem 0;">
					<a href="@if (Auth::user()->isAdmin())
								{{ route('adminDashboard') }}
							@elseif (Auth::user()->isHrManager())
								{{ route('hrManagerDashboard') }}
							@elseif (Auth::user()->isManager())
								{{ route('managerDashboard') }}
							@else
								{{ route('employeeDashboard') }}
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
					<a href="@if (Auth::user()->isAdmin())
								{{ route('adminDashboard') }}"
							@elseif (Auth::user()->isHrManager())
								{{ route('hrManagerDashboard') }}"
							@elseif (Auth::user()->isManager())
								{{ route('managerDashboard') }}
							@else
								{{ route('employeeDashboard') }}
							@endif" 
					
						class="dropdown-toggle no-arrow">
						<span class="micon dw dw-house-1"></span><span class="mtext">Home</span>
					</a>
				</li>
				@if (Auth::user()->isAdmin())
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-id-card1"></span><span class="mtext">Admin</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('addAdmin') }}">Add Admin</a></li>
							<li><a href="{{ route('manageAdmin') }}">Manage Admin</a></li>
						</ul>
					</li>
				@endif
				@if (Auth::user()->isAdmin())
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-notepad-2"></span><span class="mtext">Memorandum</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('createMemo') }}">Create Memorandum</a></li>
							<li><a href="{{ route('manageMemo') }}">Manage Memorandum</a></li>
							{{-- <li class="dropdown">
								<a href="javascript:;" class="dropdown-toggle">
									<span class="micon dw dw-list pl-2"></span><span class="mtext">Manage Memorandum</span>
								</a>
								<ul class="submenu child">
									<li><a href="javascript:;">Scheduled Memorandum</a></li>
									<li><a href="javascript:;">All Memorandum</a></li>
								</ul>
							</li> --}}
						</ul>
					</li>
				@endif
				@if (Auth::user()->isAdmin() || Auth::user()->isHrManager())
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
							<li><a href="{{ route('addEmployee') }}">Add Employee</a></li>
							<li><a href="{{ route('manageEmployee') }}">Manage Employee</a></li>
						</ul>
					</li>
					
				@endif

				@if (Auth::user()->isHrManager() || Auth::user()->isManager())
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-invoice-1"></span><span class="mtext">Task</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('addTask') }}">Add Task</a></li>
							<li><a href="{{ route('manageTask') }}">Manage Task</a></li>
						</ul>
					</li>
				@endif

				@if (Auth::user()->isAdmin() || Auth::user()->isEmployee())
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-invoice-1"></span><span class="mtext">Task</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('manageTask') }}">Manage Task</a></li>
						</ul>
					</li>
				@endif

				@if (Auth::user()->isAdmin() || Auth::user()->isHrManager())
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-invoice"></span><span class="mtext">Benefit Claim</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('addBenefitClaim') }}">Add Benefit Claim</a></li>
							<li><a href="{{ route('manageBenefitClaim') }}">Manage Benefit Claim</a></li>
							<li><a href="{{ route('manageClaimRequest') }}">Manage Claim Request</a></li>
						</ul>
					</li>
				@endif

				@if (Auth::user()->isManager() || Auth::user()->isEmployee())
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-invoice"></span><span class="mtext">Benefit Claim</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('applyBenefitClaim') }}">Apply Benefit Claim</a></li>
							<li><a href="{{ route('manageClaimRequest') }}">Manage Benefit Claim</a></li>
						</ul>
					</li>
				@endif

				@if (Auth::user()->isAdmin() || Auth::user()->isHrManager())
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

				@if (Auth::user()->isHrManager() || Auth::user()->isEmployee())
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-presentation-2"></span><span class="mtext">Training Program</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('manageTrainingProgram') }}">Manage Training Program</a></li>
						</ul>
					</li>
				@endif

				<li>
					<div class="dropdown-divider"></div>
				</li>
				<li>
					<div class="sidebar-small-cap">Section Divider</div>
				</li>
			</ul>
		</div>
	</div>
</div>