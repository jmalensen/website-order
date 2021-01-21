@extends('layouts.app')
@section('pagetitle')@lang('orders.order')&nbsp; {{ $order->code }}@endsection
@section('breadcrumbs')
	@include('layouts.inc.breadcrumbs', [
		'breadcrumbs' => [
			['name' => 'orders.orders', 'route' => 'admin.orders.index'],
			['name' => $order->code],
		]
		])
@endsection

@section('content')
	@if($order->trashed())
		<div class="alert alert-danger">
			@lang('orders.trashed')
		</div>
	@endif
	<div class="row">
		<div class="col-md-6">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h2 class="font-w300 mb-0">@lang('commun.informations')</h2>
				<div class="buttons">
					@can('editAdmin', $order)
						<a href="{{route('admin.orders.edit', ['order' => $order->id])}}"
						   class="btn btn-hero-sm btn-hero-primary px-3">
							<i class="fa fa-edit mr-1"></i> @lang('commun.edit')
						</a>
					@endcan

					@can('closeAdmin', $order)
						<a title="@lang('orders.close')"
						   href="#"
						   data-id="{{$order->id}}"
						   class="finish btn btn-hero-sm btn-hero-success" data-toggle="tooltip">
							<i class="fa fa-check"></i>
						</a>
						<form id="finish{{$order->id}}" method="POST" action="{{ route('admin.orders.close', ['order' => $order->id]) }}" class="d-none">
							{{ csrf_field() }}
						</form>
					@endcan

					@can('pauseAdmin', $order)
						<a title="@lang('orders.problem')"
						   href="#"
						   data-id="{{$order->id}}"
						   class="pause btn btn-hero-sm btn-hero-danger" data-toggle="tooltip">
							<i class="fa fa-exclamation-circle"></i>
						</a>
						<form id="pause{{$order->id}}" method="POST" action="{{ route('admin.orders.pause', ['order' => $order->id]) }}" class="d-none">
							{{ csrf_field() }}
						</form>
					@endcan

					@can('cancelAdmin', $order)
						<a title="@lang('orders.cancel')"
						   href="#"
						   data-id="{{$order->id}}"
						   class="cancel btn btn-hero-sm btn-hero-secondary" data-toggle="tooltip">
							<i class="fa fa-times"></i>
						</a>
						<form id="cancel{{$order->id}}" method="POST" action="{{ route('admin.orders.cancel', ['order' => $order->id]) }}" class="d-none">
							{{ csrf_field() }}
						</form>
					@endcan

					<a href="{{route('admin.orders.index')}}"
					   class="btn btn-hero-sm btn-light btn-sm px-3">
						<i class="fa fa-arrow-circle-left mr-1"></i> @lang('commun.back')
					</a>
				</div>
			</div>

			<div class="block border-left border-3x border-primary">
				<div class="block-content block-content-full">
					<p>
						<span class="font-weight-bold">@lang("orders.status") :</span> {!! $order->getStatusHTML() !!}
					</p>
					<p>
						<span class="font-weight-bold">@lang("orders.date_entered") :</span> {{ Helpers::datetime($order->date_entered) }}
					</p>
					<p>
						<span class="font-weight-bold">@lang("orders.date_closed") :</span> {{ Helpers::datetime($order->date_closed) }}
					</p>
					<p>
						<span class="font-weight-bold">@lang("users.user") :</span> {{ (!empty($order->user)) ? $order->user->getLibelle() : '' }}
					</p>
					<p>
						<span class="font-weight-bold">@lang("customers.company_name") :</span> {{ data_get($order, 'customer.company_name') }}
					</p>

					@can('seeAdminSettings', \App\User::class)
						<p>
							<span class="font-weight-bold">@lang("groupes.groupe") :</span> {{ data_get($order, 'groupe.name') }}
						</p>
					@endcan
				</div>
			</div>
		</div>
		<div class="col-md-6">
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead class="thead-light">
			<tr>
				<th class="text-capitalize">@lang("products.code")</th>
				<th class="text-capitalize">@lang("products.name")</th>
				@can('seeAdminSettings', \App\User::class)
					<th class="text-capitalize">@lang("products.price_ht")</th>
				@endcan
				<th class="text-capitalize">@lang('products.nb_products')</th>
				@if($order->canViewLosses())
					<th class="text-capitalize">@lang('orders.losses')</th>
				@endif
			</tr>
			</thead>

			<tbody>
				@foreach($amountProducts as $amountP)
					<tr>
						<td>{{ $amountP->product->code }}</td>
						<td>
							<a href="{{ route('products.view', [$amountP->product]) }}">
								{{ $amountP->product->name }}
							</a>
						</td>
						@can('seeAdminSettings', \App\User::class)
							<td>{{Helpers::amount($amountP->product->price_ht)}}</td>
						@endcan
						<td>{{$amountP->amount}}</td>
						@if($order->canViewLosses())
							<td>{{$amountP->losses}}</td>
						@endif
					</tr>
				@endforeach

				@can('seeAdminSettings', \App\User::class)
					<tr>
						<td></td>
						<td><span class="font-weight-bold">@lang('orders.total') : </span></td>
						<td><span class="text-primary">{{$order->total_price}}€</span></td>
						<td></td>
						@if($order->canViewLosses())
							<td></td>
						@endif
					</tr>
				@endcan
			</tbody>
		</table>
	</div>

@endsection

@push('js_after')
	<script type="text/javascript">
		$(document).ready(function() {

			$('.finish').on('click', function(e){
				e.preventDefault();
				$(this).next().submit();
			});

			$('.pause').on('click', function(e){
				e.preventDefault();
				$(this).next().submit();
			});

			$('.cancel').on('click', function(e){
				e.preventDefault();
				$(this).next().submit();
			});
		});

	</script>
@endpush
