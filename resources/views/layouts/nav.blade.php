<div class="header">
	<div class="header-left">
		<div class="menu-icon dw dw-menu"></div>
			<h3 style="padding-left: 40px; color: darkblue;">
				@yield("pageTitle")
			</h3>
	</div>
	<div class="header-right">
		{{-- <div class="user-notification">
			<div class="dropdown">
				<a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
					<i class="icon-copy dw dw-notification"></i>
					<span class="badge notification-active"></span>
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<div class="notification-list mx-h-350 customscroll">
						<ul>
							<li>
								<a href="#">
									<img src="{{ asset('vendors/images/img.jpg') }}" alt="">
									<h3>John Doe</h3>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
								</a>
							</li>
							<li>
								<a href="#">
									<img src="{{ asset('vendors/images/photo1.jpg') }}" alt="">
									<h3>Lea R. Frith</h3>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
								</a>
							</li>
							<li>
								<a href="#">
									<img src="{{ asset('vendors/images/photo2.jpg') }}" alt="">
									<h3>Erik L. Richards</h3>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
								</a>
							</li>
							<li>
								<a href="#">
									<img src="{{ asset('vendors/images/photo3.jpg') }}" alt="">
									<h3>John Doe</h3>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
								</a>
							</li>
							<li>
								<a href="#">
									<img src="{{ asset('vendors/images/photo4.jpg') }}" alt="">
									<h3>Renee I. Hansen</h3>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
								</a>
							</li>
							<li>
								<a href="#">
									<img src="{{ asset('vendors/images/img.jpg') }}" alt="">
									<h3>Vicki M. Coleman</h3>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div> --}}
		<div class="user-info-dropdown">
			<div class="dropdown pt-10">
				<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
					<span class="user-name">{{ Auth::user()->getDepartment->departmentName }} | {{ Auth::user()->getFullName() }}</span>
				</a>
				<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
					<a class="dropdown-item" href="{{ route('viewProfile') }}"><i class="dw dw-user1"></i> View Profile</a>
					<a class="dropdown-item" href="{{ route('changePassword') }}"><i class="dw dw-padlock1"></i> Change Password</a>
					<a class="dropdown-item" href="{{ route('logout') }}"><i class="dw dw-logout"></i> Log Out</a>
				</div>
			</div>
		</div>
	</div>
</div>