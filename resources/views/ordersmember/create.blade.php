@extends('layouts.app')
@section('pagetitle')@lang('orders.add')@endsection
@section('breadcrumbs')
	@include('layouts.inc.breadcrumbs', [
		'breadcrumbs' => [
			['name' => 'orders.orders', 'route' => 'member.orders.index'],
			['name' => 'commun.add'],
		]
		])
@endsection

@section('content')
	@include('ordersmember.form')
@endsection

