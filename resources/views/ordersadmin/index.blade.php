@extends('layouts.app')
@section('pagetitle')@lang('orders.index')@endsection

@section('content')
	<div class="block">
		<div class="block-content block-content-full border-left border-3x border-primary">
			@can('createAdmin', \App\Models\Order::class)
				<a href="{{route('admin.orders.create')}}"
				   class="btn btn-hero-sm btn-hero-primary float-right">
					<i class="fa fa-plus"></i> @lang('commun.add')
				</a>
			@endcan
			@can('seeAdminSettings', \App\User::class)
				<a href="{{route('admin.orders.massmodify')}}"
				   class="btn btn-hero-sm btn-hero-info float-right mr-2">
					<i class="fa fa-edit"></i> @lang('orders.massmodify')
				</a>
			@endcan
				<div class="clearfix mb-2"></div>
			<div class="row">
				<div class="col-md-12">
					{{Form::open(['method' => 'GET'])}}
					{{Form::considerRequest(true)}}
					{{Form::searchGroup('search')}}
					{{Form::close()}}
				</div>
			</div>

			@include('layouts.orders.listorders')
			{{ $orders->appends(\Request::except('page'))->render() }}
		</div>
	</div>

@endsection

@push('js_after')
<script type="text/javascript">
	$(document).ready(function() {

		$('.finish').on('click', function(e){
			e.preventDefault();
			$(this).next().submit();
		});

		$('.pause').on('click', function(e){
			e.preventDefault();
			$(this).next().submit();
		});

		$('.cancel').on('click', function(e){
			e.preventDefault();
			$(this).next().submit();
		});
	});
</script>
@endpush