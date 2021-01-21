@extends('layouts.app')
@section('pagetitle')@lang('commun.home')@endsection
@section('breadcrumbs')
	@include('layouts.inc.breadcrumbs', [
		'breadcrumbs' => [
		]
		])
@endsection

@section('content')
	<div class="row">
		<div class="col-6 col-md-4 col-xl-3">
			<a class="block block-link-pop text-center" href="{{route('users.index')}}">
				<div class="block-content block-content-full aspect-ratio-16-9 d-flex justify-content-center align-items-center">
					<div>
						<i class="far fa-2x fa-user"></i>
						<div class="font-w600 mt-3 text-uppercase">@lang('users.users')</div>
					</div>
				</div>
			</a>
		</div>

		<div class="col-6 col-md-4 col-xl-3">
			<a class="block block-link-pop text-center" href="{{route('admin.orders.index')}}">
				<div class="block-content block-content-full aspect-ratio-16-9 d-flex justify-content-center align-items-center">
					<div>
						<i class="far fa-2x fa-file-alt"></i>
						<div class="font-w600 mt-3 text-uppercase">@lang('orders.orders')</div>
					</div>
				</div>
			</a>
		</div>

		<div class="col-6 col-md-4 col-xl-3">
			<a class="block block-link-pop text-center" href="{{route('customers.index')}}">
				<div class="block-content block-content-full aspect-ratio-16-9 d-flex justify-content-center align-items-center">
					<div>
						<i class="far fa-2x fa-user-tie"></i>
						<div class="font-w600 mt-3 text-uppercase">@lang('customers.customers')</div>
					</div>
				</div>
			</a>
		</div>

		<div class="col-6 col-md-4 col-xl-3">
			<a class="block block-link-pop text-center" href="{{route('products.index')}}">
				<div class="block-content block-content-full aspect-ratio-16-9 d-flex justify-content-center align-items-center">
					<div>
						<i class="far fa-2x fa-barcode"></i>
						<div class="font-w600 mt-3 text-uppercase">@lang('products.products')</div>
					</div>
				</div>
			</a>
		</div>
	</div>
@endsection
