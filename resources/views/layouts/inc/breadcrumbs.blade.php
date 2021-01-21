<nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
	<ol class="breadcrumb">
	<li class="breadcrumb-item">
		<i class="icon fa fa-home"></i>
		@can('manageuser')
			<a href="{{route('admin.home')}}">@lang('commun.home')</a>
		@else
			<a href="{{route('member.home')}}">@lang('commun.home')</a>
		@endcan
	</li>
		@if (isset($breadcrumbs) && count($breadcrumbs) > 0)
			@foreach($breadcrumbs as $bread)
				@if (!$loop->last)
					<li class="breadcrumb-item">
						{!! link_to_route($bread['route'], __($bread['name']), data_get($bread, 'params', [])) !!}
					</li>
				@else
					<li class="breadcrumb-item active">
						@lang($bread['name'])
					</li>
				@endif
			@endforeach
		@endif
	</ol>
</nav>