@extends('layouts.app')
@section('pagetitle'){{$customer->company_name}}&nbsp;@endsection
@section('breadcrumbs')
	@include('layouts.inc.breadcrumbs', [
		'breadcrumbs' => [
			['name' => 'customers.customers', 'route' => 'customers.index'],
			['name' => $customer->company_name],
		]
		])
@endsection

@section('content')
	@if($customer->trashed())
		<div class="alert alert-danger">
			@lang('customers.trashed')
		</div>
	@endif
	<div class="row">
		<div class="col-md-6">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h2 class="font-w300 mb-0">@lang('commun.informations')</h2>
				<div class="buttons">
					@can('edit', $customer)
						<a href="{{route('customers.edit', ['customer' => $customer->id])}}"
						   class="btn btn-hero-sm btn-hero-primary px-3">
							<i class="fa fa-edit mr-1"></i> @lang('commun.edit')
						</a>
					@endcan

					<a href="{{route('customers.index')}}"
					   class="btn btn-hero-sm btn-light btn-sm px-3">
						<i class="fa fa-arrow-circle-left mr-1"></i> @lang('commun.back')
					</a>
				</div>
			</div>
			<div class="block border-left border-3x border-primary">
				<div class="block-content  block-content-full">
					<p>
						<span class="font-weight-bold">@lang("customers.address") :</span> {{ $customer->address }}
					</p>
					<p>
						<span class="font-weight-bold">@lang("customers.city") :</span> {{ $customer->city }}
					</p>
					<p>
						<span class="font-weight-bold">@lang("customers.postcode") :</span> {{ $customer->postcode }}
					</p>
					<p>
						<span class="font-weight-bold">@lang("customers.country") :</span> {{ $customer->country }}
					</p>
					<p>
						<span class="font-weight-bold">@lang("customers.phone") :</span> {{ $customer->phone }}
					</p>
					<p>
						<span class="font-weight-bold">@lang("users.user_appro") :</span> {{ data_get($customer, 'user.libelle') }}
					</p>
					@can('seeAdminSettings', \App\User::class)
						<p>
							<span class="font-weight-bold">@lang("groupes.groupe") :</span> {{ data_get($customer, 'groupe.name') }}
						</p>
					@endcan
				</div>
			</div>
		</div>
		<div class="col-md-6">
		</div>
	</div>

	@if($orders->count() > 0)
		@include('layouts.orders.listorders')
		{{ $orders->appends(\Request::except('page'))->render() }}
	@endif

@endsection
