<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="author" content="Julien">

	<title>{{ config('app.name', 'Laravel') }}</title>

	@routes {{--Ziggy routes for js--}}
<!-- Fonts and Styles -->
	@stack('css_before')
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700">
	<link rel="stylesheet" href="{{ mix('css/dashmix.css') }}">

{{--<link rel="stylesheet" href="{{ mix('css/themes/xmodern.css') }}">--}}
@stack('css_after')

<!-- Scripts -->
	<script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
</head>
<body>

<div id="page-container"
	 class="sidebar-o side-scroll page-header-fixed page-header-dark main-content-narrow">

	<nav id="sidebar" aria-label="Main Navigation">
		<!-- Side Header -->
		<div class="bg-header-dark">
			<div class="content-header bg-white-10">
				<!-- Logo -->
				<a class="link-fx font-w600 font-size-lg text-white" href="/">
					<img style="height:60px;" class="img-fluid" src="{{asset('images/logojmd.png')}}" alt="{{ config('app.name') }}" />
				</a>
				<!-- END Logo -->

				<!-- Options -->
				<div>
					<!-- Close Sidebar, Visible only on mobile screens -->
					<!-- Layout API, functionality initialized in Template._uiApiLayout() -->
					<a class="d-lg-none text-white ml-2" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
						<i class="fa fa-times-circle"></i>
					</a>
					<!-- END Close Sidebar -->
				</div>
				<!-- END Options -->
			</div>
		</div>
		<!-- END Side Header -->

		<!-- Side Navigation -->
		@can('manageuser')
			@include('layouts.inc.menuadmin')
		@else
			@include('layouts.inc.menumember')
		@endcan
	<!-- END Side Navigation -->
	</nav>
	<!-- END Sidebar -->

	<!-- Header -->
	<header id="page-header">
		<!-- Header Content -->
		<div class="content-header">
			<!-- Left Section -->
			<div>
				<!-- Toggle Sidebar -->
				<!-- Layout API, functionality initialized in Template._uiApiLayout()-->
				<button type="button" class="btn btn-dual mr-1" data-toggle="layout" data-action="sidebar_toggle">
					<i class="fa fa-fw fa-bars"></i>
				</button>
				<!-- END Toggle Sidebar -->

				<!-- Open Search Section -->
				<!-- Layout API, functionality initialized in Template._uiApiLayout() -->
			{{--<button type="button" class="btn btn-dual" data-toggle="layout" data-action="header_search_on">
				<i class="fa fa-fw fa-search"></i> <span
						class="ml-1 d-none d-sm-inline-block">@lang('commun.search')</span>
			</button>--}}
			<!-- END Open Search Section -->
			</div>
			<!-- END Left Section -->

			<!-- Right Section -->
			<div>
				<!-- User Dropdown -->
				<div class="dropdown d-inline-block">
					<button type="button" class="btn btn-dual" id="page-header-user-dropdown" data-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-fw fa-user d-sm-none"></i>
						<span class="d-none d-sm-inline-block">{{Auth::user()->getLibelle()}}</span>
						<i class="fa fa-fw fa-angle-down ml-1 d-none d-sm-inline-block"></i>
					</button>
					<div class="dropdown-menu dropdown-menu-right p-0" aria-labelledby="page-header-user-dropdown">
						<div class="bg-primary-darker rounded-top font-w600 text-white text-center p-3">
							{{Auth::user()->getLibelle()}}
						</div>
						<div class="p-2">
							<a href="{{route('users.changepassword')}}" class="dropdown-item">
								<i class="far fa-fw fa-user mr-1"></i> @lang('users.myaccount')
							</a>
							<a class="dropdown-item" href="{{ route('logout') }}"
							   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
								<i class="far fa-fw fa-arrow-alt-circle-left mr-1"></i> @lang('commun.logout')
							</a>
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								{{ csrf_field() }}
							</form>
						</div>
					</div>
				</div>
				<!-- END User Dropdown -->

			</div>
			<!-- END Right Section -->
		</div>
		<!-- END Header Content -->

		<!-- Header Loader -->
		<!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
		<div id="page-header-loader" class="overlay-header bg-primary-darker">
			<div class="content-header">
				<div class="w-100 text-center">
					<i class="fa fa-fw fa-2x fa-sun fa-spin text-white"></i>
				</div>
			</div>
		</div>
		<!-- END Header Loader -->
	</header>
	<!-- END Header -->

	<!-- Main Container -->
	<main id="main-container">
		<!-- Hero -->
		<div class="bg-body-light">
			<div class="content content-full">
				<div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
					<h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">@yield('pagetitle')</h1>
					<!-- START BREADCRUMB -->
				@yield('breadcrumbs')
				<!-- END BREADCRUMB -->
				</div>
			</div>
		</div>
		<!-- END Hero -->
		<div class="content">
			@yield('content')
		</div>
	</main>
	<!-- END Main Container -->

</div>
<!-- END Page Container -->
@stack('js_before')
<!-- Dashmix Core JS -->
<script src="{{ mix('js/dashmix.app.js') }}"></script>
@include('flash')

<script src="{{ mix('js/default.js') }}"></script>
<!-- Laravel Scaffolding JS -->
<script src="{{ mix('js/laravel.app.js') }}"></script>
@stack('js_after')
</div>

</body>
</html>
