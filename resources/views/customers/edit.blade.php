@extends('layouts.app')
@section('pagetitle')@lang('customers.edit')@endsection
@section('breadcrumbs')
	@include('layouts.inc.breadcrumbs', [
		'breadcrumbs' => [
			['name' => 'customers.customers', 'route' => 'customers.index'],
			['name' => 'commun.edit'],
		]
		])
@endsection

@section('content')
	@include('customers.form')
@endsection

