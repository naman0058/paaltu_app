<div class="sidebar p-2 py-md-3 @@cardClass">
	<div class="container-fluid">
		<!-- sidebar: title-->
		<div class="title-text d-flex align-items-center mb-4 mt-1">
			<h4 class="sidebar-title mb-0 flex-grow-1"> <img src="{{ asset('assets/admin/img/paaltu.jpg') }}"
					style="width:50px" style="height: 50px">&nbsp;&nbsp;<span style="color:violet">paal</span><span
					style="color:black">tu</span></h4>

		</div>
		<!-- sidebar: Create new -->

		<!-- sidebar: menu list -->
		<div class="main-menu flex-grow-1">
			<ul class="menu-list">
				<li>
					<a class="m-link" href="{{ route('dashboard')}}">
						<i class="fa fa-tachometer" aria-hidden="true"></i>
						<span class="ms-2">My Dashboard</span>
					</a>
				</li>
				@if (auth()->user()->user_type == 'admin')
					<li>
					<a class="m-link" href="{{ url('vendors')}}">
						<i class="fa fa-building" aria-hidden="true"></i>
						<span class="ms-2">Vendor</span>
					</a>
				</li>
				<li>
					<a class="m-link" href="{{ url('pet-category')}}">
						<i class="fa fa fa-credit-card"></i>
						<span class="ms-2">Pet Category</span>
					</a>
				</li>
				<li>
					<a class="m-link" href="{{ url('breed')}}">
						<i class="fa fa-paw" aria-hidden="true"></i>
						<span class="ms-2">Breed</span>
					</a>
				</li>
				<li>
					<a class="m-link" href="{{ url('pet-profile')}}">
						<i class="fa fa-user" aria-hidden="true"></i>
						<span class="ms-2">Pet Profile</span>
					</a>
				</li>
				<li>
					<a class="m-link" href="{{ url('service')}}">
						<i class="fa fa-users" aria-hidden="true"></i>
						<span class="ms-2">Service</span>
					</a>
				</li>
				<li>
					<a class="m-link" href="{{ url('booking')}}">
						<i class="fa fa-shopping-cart" aria-hidden="true"></i>
						<span class="ms-2">Bookings</span>
					</a>
				</li>
				
				<li>
					<a class="m-link" href="{{ url('interest')}}">
						<i class="fa fa fa-heart"></i>
						<span class="ms-2">Interest</span>
					</a>
				</li>
				<li>
					<a class="m-link" href="{{ url('blog')}}">
						<i class="fa fa-image" aria-hidden="true"></i>
						<span class="ms-2">Blog</span>
					</a>
				</li>
				<li>
					<a class="m-link" href="{{ url('setting')}}">
						<i class="fa fa-cog" aria-hidden="true"></i>
						<span class="ms-2">Settings</span>
					</a>
				</li>
				@endif
				@if(auth()->user()->user_type == 'vendor')
					<li>
						<a class="m-link" href="{{ url('my-service')}}">
							<i class="fa fa-users" aria-hidden="true"></i>
							<span class="ms-2">Service</span>
						</a>
					</li>
					<li>
						<a class="m-link" href="{{ url('my-booking')}}">
							<i class="fa fa-shopping-cart" aria-hidden="true"></i>
							<span class="ms-2">Bookings</span>
						</a>
					</li>
				@endif
			</ul>
		</div>
		<!-- sidebar: footer link -->
		<!-- sidebar: footer link -->
		<ul class="menu-list nav navbar-nav flex-row text-center menu-footer-link">
			<li class="nav-item flex-fill p-2">
				<a class="d-inline-block w-100 color-400" href="#" data-bs-toggle="modal"
					data-bs-target="#ScheduleModal" title="My Schedule">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" fill="currentColor" viewBox="0 0 16 16">
						<path class="fill-secondary"
							d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
						<path
							d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z" />
						<path class="fill-secondary"
							d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4z" />
					</svg>
				</a>
			</li>
			<li class="nav-item flex-fill p-2">
				<a class="d-inline-block w-100 color-400" href="#" data-bs-toggle="modal" data-bs-target="#MynotesModal"
					title="My notes">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" fill="currentColor" viewBox="0 0 16 16">
						<path class="fill-secondary"
							d="M1.5 0A1.5 1.5 0 0 0 0 1.5V13a1 1 0 0 0 1 1V1.5a.5.5 0 0 1 .5-.5H14a1 1 0 0 0-1-1H1.5z" />
						<path
							d="M3.5 2A1.5 1.5 0 0 0 2 3.5v11A1.5 1.5 0 0 0 3.5 16h6.086a1.5 1.5 0 0 0 1.06-.44l4.915-4.914A1.5 1.5 0 0 0 16 9.586V3.5A1.5 1.5 0 0 0 14.5 2h-11zM3 3.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 .5.5V9h-4.5A1.5 1.5 0 0 0 9 10.5V15H3.5a.5.5 0 0 1-.5-.5v-11zm7 11.293V10.5a.5.5 0 0 1 .5-.5h4.293L10 14.793z" />
					</svg>
				</a>
			</li>
			<li class="nav-item flex-fill p-2">
				<a class="d-inline-block w-100 color-400" href="#" data-bs-toggle="modal" data-bs-target="#RecentChat">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" fill="currentColor" viewBox="0 0 16 16">
						<path
							d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
						<path class="fill-secondary"
							d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z" />
					</svg>
				</a>
			</li>
			<li class="nav-item flex-fill p-2">
				<a class="d-inline-block w-100 color-400" href="{{route('logout')}}" title="sign-out">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" fill="currentColor" viewBox="0 0 16 16">
						<path d="M7.5 1v7h1V1h-1z" />
						<path class="fill-secondary"
							d="M3 8.812a4.999 4.999 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812z" />
					</svg>
				</a>
			</li>
		</ul>
	</div>
</div>