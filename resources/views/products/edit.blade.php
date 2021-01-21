@extends('layouts.app')
@section('pagetitle')@lang('products.edit')@endsection
@section('breadcrumbs')
	@include('layouts.inc.breadcrumbs', [
		'breadcrumbs' => [
			['name' => 'products.products', 'route' => 'products.index'],
			['name' => 'commun.edit'],
		]
		])
@endsection

@section('content')
	@include('products.form')
@endsection

