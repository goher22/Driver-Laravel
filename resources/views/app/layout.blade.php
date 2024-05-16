@extends('layouts.app')

@section('content')
<nav class="navbar navbar-expand-lg navbar-bg">
	<div class="navbar-brand">
		<a href="{{route('home')}}" class="text-light">{{setting('app.name')}}</a>
	</div>

	<div class="d-md-none ml-auto mt-2 mt-lg-0">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
			<i class="material-icons text-light">supervisor_account</i>
		</button>
		<button class="navbar-toggler sidebar-mobile-main-toggle" type="button" id="menu-toggle">
			<i class="material-icons text-light">menu</i>
		</button>
	</div>

	<div class="collapse navbar-collapse" id="navbar-mobile">
		<ul class="navbar-nav ml-auto mt-2 mt-lg-0">
			<li class="nav-item dropdown">
				<a href="#" class="navbar-nav-link dropdown-toggle text-light" data-toggle="dropdown">
					{{Auth::user()->email}}
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<a href="{{route('account.index')}}" class="dropdown-item">
						<i class="material-icons md-22">supervisor_account</i> {{__('Account Settings')}}
					</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="material-icons">exit_to_app</i> {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
				</div>
			</li>
		</ul>
	</div>
</nav>

<div class="d-flex" id="wrapper">
	<!-- Sidebar -->
	<div class="sidebar border-right" id="sidebar-wrapper">
		<div class="list-group list-group-flush">
			<a href="{{route('home')}}" class="list-group-item list-group-item-action"><i class="material-icons">home</i> {{__('Dashboard')}}</a>
			@can('users_access')
				<a href="{{route('users.index')}}" class="list-group-item list-group-item-action"><i class="material-icons">supervisor_account</i> {{__('Users')}}</a>
			@endcan

			@can('activitylog_access')
				<a href="{{route('activitylog.index')}}" class="list-group-item list-group-item-action"><i class="material-icons">list</i> {{__('Activity Log')}}</a>
			@endcan

			@can('roles_access')
				<a href="{{route('roles.index')}}" class="list-group-item list-group-item-action"><i class="material-icons">perm_data_setting</i> {{__('Roles')}}</a>
			@endcan

			@can('permissions_access')	
				<a href="{{route('permissions.index')}}" class="list-group-item list-group-item-action"><i class="material-icons">lock_open</i> {{__('Permissions')}}</a>
			@endcan

			@role('admin')	
				<a href="{{route('settings.app')}}" class="list-group-item list-group-item-action"><i class="material-icons">settings</i> {{__('Settings')}}</a>
			@endrole
		</div>
	</div>
	<!-- /#sidebar-wrapper -->

	<!-- Page Content -->
	<div id="page-content-wrapper">
		@yield('sub_content')
	</div>
	<!-- /#page-content-wrapper -->
</div>
@endsection