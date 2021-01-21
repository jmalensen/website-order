@extends('layouts.app')
@section('pagetitle')@lang('orders.edit')@endsection
@section('breadcrumbs')
	@include('layouts.inc.breadcrumbs', [
		'breadcrumbs' => [
			['name' => 'orders.orders', 'route' => 'admin.orders.index'],
			['name' => 'commun.edit'],
		]
		])
@endsection

@section('content')
	@include('ordersadmin.form')
@endsection

