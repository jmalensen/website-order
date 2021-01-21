@extends('layouts.app')
@section('pagetitle')@lang('groupes.edit')@endsection
@section('breadcrumbs')
	@include('layouts.inc.breadcrumbs', [
		'breadcrumbs' => [
			['name' => 'groupes.groupes', 'route' => 'groupes.index'],
			['name' => 'commun.edit'],
		]
		])
@endsection

@section('content')
	@include('groupes.form')
@endsection

