@extends('layouts.app')
@section('pagetitle')@lang('products.add')@endsection
@section('breadcrumbs')
	@include('layouts.inc.breadcrumbs', [
		'breadcrumbs' => [
			['name' => 'products.products', 'route' => 'products.index'],
			['name' => 'commun.add'],
		]
		])
@endsection

@section('content')
	@include('products.form')
@endsection

