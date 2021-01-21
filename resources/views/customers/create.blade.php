@extends('layouts.app')
@section('pagetitle')@lang('customers.add')@endsection
@section('breadcrumbs')
	@include('layouts.inc.breadcrumbs', [
		'breadcrumbs' => [
			['name' => 'customers.customers', 'route' => 'customers.index'],
			['name' => 'commun.add'],
		]
		])
@endsection

@section('content')
	@include('customers.form')
@endsection

