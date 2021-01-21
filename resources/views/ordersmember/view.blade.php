@extends('layouts.app')
@section('pagetitle')@lang('orders.order')&nbsp;{{ $order->code }}@endsection
@section('breadcrumbs')
	@include('layouts.inc.breadcrumbs', [
		'breadcrumbs' => [
			['name' => 'orders.orders', 'route' => 'member.orders.index'],
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
					@can('editMember', $order)
						@if($canDoOrder)
							<a href="{{route('member.orders.edit', ['order' => $order->id])}}"
							   class="btn btn-hero-sm btn-hero-primary px-3">
								<i class="fa fa-edit mr-1"></i> @lang('commun.edit')
							</a>
						@endif
					@endcan

					<a href="{{route('member.orders.index')}}"
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
						<span class="font-weight-bold">@lang("orders.code") :</span> {{ $order->code }}
					</p>
					<p>
						<span class="font-weight-bold">@lang("orders.date_entered") :</span> {{ Helpers::datetime($order->date_entered) }}
					</p>
					<p>
						<span class="font-weight-bold">@lang("orders.date_closed") :</span> {{ Helpers::datetime($order->date_closed) }}
					</p>
					<p>
						<span class="font-weight-bold">@lang("customers.company_name") :</span> {{ data_get($order, 'customer.company_name') }}
					</p>
				</div>


				<div class="table-responsive">
					@if($order->canPutLosses() )
						{{ Form::model($order, ['route' => ['member.orders.storelosses', $order->id], 'method' => 'POST']) }}
					@endif

					<table class="table table-striped table-hover">
						<thead class="thead-light">
						<tr>
							<th class="text-capitalize">@lang("products.code")</th>
							<th class="text-capitalize">@lang("products.name")</th>
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
									{{ $amountP->product->name }}
								</td>
								<td>{{$amountP->amount}}</td>

								@if($order->canPutLosses() )
									<td>
										{{ Form::openGroup('products['.$amountP->product_id.']') }}
										{{ Form::text('products['.$amountP->product_id.']', $amountP->losses) }}
										{{ Form::closeGroup() }}
									</td>
								@elseif($order->canViewLosses())
									<td>{{$amountP->losses}}</td>
								@endif
							</tr>
						@endforeach
						</tbody>
					</table>

					@if($order->canPutLosses() )
						<div class="block-content block-content-full block-content-sm bg-body-light text-right">
							{{ Form::cancelButton(route('member.orders.index')) }}
							{{ Form::submitButton('Valider pertes') }}
						</div>

						{{ Form::close() }}
					@endif
				</div>
			</div>
		</div>
		<div class="col-md-6">
		</div>
	</div>


@endsection
