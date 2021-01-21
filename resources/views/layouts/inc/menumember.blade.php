<div class="content-side content-side-full">
	<ul class="nav-main">
		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('member.home') ? ' active' : '' }}" href="{{route('member.home')}}">
				<i class="nav-main-link-icon si si-cursor"></i>
				<span class="nav-main-link-name">@lang('commun.home')</span>
			</a>
		</li>
		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('member.orders.index') ? ' active' : '' }}" href="{{route('member.orders.index')}}">
				<i class="nav-main-link-icon fa fa-file-alt"></i>
				<span class="nav-main-link-name">@lang('orders.orders')</span>
			</a>
		</li>
	</ul>
</div>
