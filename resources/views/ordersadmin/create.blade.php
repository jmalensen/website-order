@extends('layouts.app')
@section('pagetitle')@lang('orders.add')@endsection
@section('breadcrumbs')
	@include('layouts.inc.breadcrumbs', [
		'breadcrumbs' => [
			['name' => 'orders.orders', 'route' => 'admin.orders.index'],
			['name' => 'commun.add'],
		]
		])
@endsection

@section('content')
	@include('ordersadmin.form')
@endsection

