<div class="content-side content-side-full">
	<ul class="nav-main">
		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('admin.home') ? ' active' : '' }}" href="{{route('admin.home')}}">
				<i class="nav-main-link-icon si si-cursor"></i>
				<span class="nav-main-link-name">@lang('commun.home')</span>
			</a>
		</li>

		<li class="nav-main-item{{ request()->routeIs('stats.day') || request()->routeIs('stats.range') ? ' open' : '' }}">
			<a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
			   aria-expanded="true" href="#">
				<i class="nav-main-link-icon fa fa-chart-bar"></i>
				<span class="nav-main-link-name">@lang('commun.statistiques')</span>
			</a>
			<ul class="nav-main-submenu">
				<li class="nav-main-item">
					<a class="nav-main-link{{ request()->routeIs('stats.day') ? ' active' : '' }}"
					   href="{{route('stats.day')}}">
						<span class="nav-main-link-name">@lang('commun.stats_day')</span>
					</a>
				</li>
				<li class="nav-main-item">
					<a class="nav-main-link{{ request()->routeIs('stats.range') ? ' active' : '' }}"
					   href="{{route('stats.range')}}">
						<span class="nav-main-link-name">@lang('commun.stats_range')</span>
					</a>
				</li>
			</ul>
		</li>

		@can('seeAdminSettings', \App\User::class)
			<li class="nav-main-item">
				<a class="nav-main-link{{ request()->routeIs('groupes.index') ? ' active' : '' }}" href="{{route('groupes.index')}}">
					<i class="nav-main-link-icon fa fa-users"></i>
					<span class="nav-main-link-name">@lang('groupes.index')</span>
				</a>
			</li>
		@endcan
		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('users.index') ? ' active' : '' }}" href="{{route('users.index')}}">
				<i class="nav-main-link-icon fa fa-user"></i>
				<span class="nav-main-link-name">@lang('users.index')</span>
			</a>
		</li>
		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('admin.orders.index') ? ' active' : '' }}" href="{{route('admin.orders.index')}}">
				<i class="nav-main-link-icon fa fa-file-alt"></i>
				<span class="nav-main-link-name">@lang('orders.orders')</span>
			</a>
		</li>
		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('customers.index') ? ' active' : '' }}" href="{{route('customers.index')}}">
				<i class="nav-main-link-icon fa fa-user-tie"></i>
				<span class="nav-main-link-name">@lang('customers.customers')</span>
			</a>
		</li>
		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('products.index') ? ' active' : '' }}" href="{{route('products.index')}}">
				<i class="nav-main-link-icon fa fa-barcode"></i>
				<span class="nav-main-link-name">@lang('products.products')</span>
			</a>
		</li>
	</ul>
</div>
