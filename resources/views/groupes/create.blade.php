@extends('layouts.app')
@section('pagetitle')@lang('groupes.add')@endsection
@section('breadcrumbs')
	@include('layouts.inc.breadcrumbs', [
		'breadcrumbs' => [
			['name' => 'groupes.groupes', 'route' => 'groupes.index'],
			['name' => 'commun.add'],
		]
		])
@endsection

@section('content')
	@include('groupes.form')
@endsection

