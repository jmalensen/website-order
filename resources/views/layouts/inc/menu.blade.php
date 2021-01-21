<div class="content-side content-side-full">
	<ul class="nav-main">
		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('home') ? ' active' : '' }}" href="{{route('home')}}">
				<i class="nav-main-link-icon si si-cursor"></i>
				<span class="nav-main-link-name">@lang('commun.home')</span>
			</a>
		</li>
		@can('manageuser')
			<li class="nav-main-item">
				<a class="nav-main-link{{ request()->routeIs('users.index') ? ' active' : '' }}" href="{{route('users.index')}}">
					<i class="nav-main-link-icon fa fa-user"></i>
					<span class="nav-main-link-name">@lang('users.index')</span>
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
		@endcan
		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('orders.index') ? ' active' : '' }}" href="{{route('orders.index')}}">
				<i class="nav-main-link-icon fa fa-file-alt"></i>
				<span class="nav-main-link-name">@lang('orders.orders')</span>
			</a>
		</li>
	</ul>
</div>
