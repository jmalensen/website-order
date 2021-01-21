@extends('layouts.app')
@section('pagetitle')@lang('groupes.groupe')&nbsp;@endsection
@section('breadcrumbs')
	@include('layouts.inc.breadcrumbs', [
		'breadcrumbs' => [
			['name' => 'groupes.groupes', 'route' => 'groupes.index'],
			['name' => 'groupes.groupe'],
		]
		])
@endsection

@section('content')
	@if($groupe->trashed())
		<div class="alert alert-danger">
			@lang('groupes.trashed')
		</div>
	@endif
	<div class="row">
		<div class="col-md-6">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h2 class="font-w300 mb-0">@lang('commun.informations')</h2>
				<div class="buttons">
					@can('edit', $groupe)
						<a href="{{route('groupes.edit', ['groupe' => $groupe->id])}}"
						   class="btn btn-hero-sm btn-hero-primary px-3">
							<i class="fa fa-edit mr-1"></i> @lang('commun.edit')
						</a>
					@endcan

					<a href="{{route('groupes.index')}}"
					   class="btn btn-hero-sm btn-light btn-sm px-3">
						<i class="fa fa-arrow-circle-left mr-1"></i> @lang('commun.back')
					</a>
				</div>
			</div>
			<div class="block border-left border-3x border-primary">
				<div class="block-content  block-content-full">
					<p>
						<span class="font-weight-bold">@lang("groupes.name") :</span> {{ $groupe->name }}
					</p>
				</div>
			</div>
		</div>
		<div class="col-md-6">
		</div>
	</div>


@endsection
